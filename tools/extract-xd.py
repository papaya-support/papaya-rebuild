"""Extract original XD content for installation and offline design reference.
XD is input data only; plugin metadata and invisible layers are never executed.
"""

import json, pathlib, copy, html, re, shutil, struct, collections

ROOT = pathlib.Path(__file__).resolve().parent.parent
SRC = ROOT / "design-source"
THEME = ROOT / "wordpress/wp-content/themes/papaya-search-child"
(SRC / "reference-data").mkdir(parents=True, exist_ok=True)
for d in ["assets/images"]:
    (THEME / d).mkdir(parents=True, exist_ok=True)
r = json.loads((SRC / "resources/graphics/graphicContent.agc").read_text())
sources = {}


def index(n):
    if isinstance(n, dict):
        if n.get("id"):
            sources[n["id"]] = n
        for v in n.values():
            index(v)
    elif isinstance(n, list):
        for v in n:
            index(v)


index(r)


def merge(a, b):
    out = copy.deepcopy(a)
    for k, v in b.items():
        if k == "type" and v == "syncRef":
            continue
        out[k] = (
            merge(out[k], v)
            if isinstance(v, dict) and isinstance(out.get(k), dict)
            else copy.deepcopy(v)
        )
    return out


unresolved = []


def resolve(n):
    if n.get("type") == "syncRef":
        if n["syncSourceGuid"] not in sources:
            unresolved.append(n["syncSourceGuid"])
            return {}
        n = merge(sources[n["syncSourceGuid"]], n)
    return n


I = [1, 0, 0, 1, 0, 0]


def matrix(t):
    return [t.get(k, v) for k, v in zip(["a", "b", "c", "d", "tx", "ty"], I)]


def mul(a, b):
    return [
        a[0] * b[0] + a[2] * b[1],
        a[1] * b[0] + a[3] * b[1],
        a[0] * b[2] + a[2] * b[3],
        a[1] * b[2] + a[3] * b[3],
        a[0] * b[4] + a[2] * b[5] + a[4],
        a[1] * b[4] + a[3] * b[5] + a[5],
    ]


def fmt(x):
    return str(round(x, 5))


def mat(m):
    return "matrix(" + " ".join(map(fmt, m)) + ")"


def esc(x):
    return html.escape(str(x), quote=True)


def col(c):
    v = c.get("value", {})
    a = c.get("alpha", 1)
    if isinstance(v, int):
        return "#%06x" % (v & 0xFFFFFF)
    return f"rgba({v.get('r',0)},{v.get('g',0)},{v.get('b',0)},{a})"


def shape(s):
    t = s.get("type")
    if t == "rect":
        return (
            "<rect "
            + " ".join(
                f'{k}="{fmt(s.get(k,0))}"' for k in ["x", "y", "width", "height"]
            )
            + f' rx="{fmt(s.get("r",[0])[0])}"/>'
        )
    if t == "path":
        return '<path d="' + esc(s["path"]) + '"/>'
    if t in ["ellipse", "circle", "line"]:
        keys = {
            "ellipse": ["cx", "cy", "rx", "ry"],
            "circle": ["cx", "cy", "r"],
            "line": ["x1", "y1", "x2", "y2"],
        }[t]
        return "<" + t + " " + " ".join(f'{k}="{fmt(s.get(k,0))}"' for k in keys) + "/>"
    return ""


assets = {}
for p in (SRC / "resources").iterdir():
    if not p.is_file():
        continue
    data = p.read_bytes()
    ext = ".png" if data.startswith(b"\x89PNG") else ".jpg"
    name = p.name + ext
    shutil.copyfile(p, THEME / "assets/images" / name)
    assets[p.name] = name
weights = {"Regular": 400, "Medium": 500, "SemiBold": 600, "Bold": 700, "Black": 900}


def font_style(n):
    st = n.get("style", {})
    f = st.get("font", {})
    a = st.get("textAttributes", {})
    rs = n.get("meta", {}).get("ux", {}).get("rangedStyles", [])
    return {
        "family": f.get("family", "Inter"),
        "weight": weights.get(f.get("style"), 400),
        "size": f.get("size", 16),
        "color": col(st.get("fill", {}).get("color", {})),
        "spacing": a.get("letterSpacing", 0) * f.get("size", 16) / 1000,
        "align": a.get("paragraphAlign", "left"),
        "lineHeight": a.get("lineHeight", f.get("size", 16) * 1.25),
        "uppercase": bool(rs and rs[0].get("textTransform") == "uppercase"),
    }


def baseline(f):
    name = f["family"].lower().replace(" ", "-") + "-" + str(f["weight"]) + ".ttf"
    p = THEME / "assets/fonts" / name
    if not p.exists():
        return 0.85
    data = p.read_bytes()
    num = struct.unpack(">H", data[4:6])[0]
    tables = {}
    for i in range(num):
        tag, checksum, offset, length = struct.unpack(
            ">4sIII", data[12 + i * 16 : 28 + i * 16]
        )
        tables[tag.decode()] = (offset, length)
    h = tables["head"][0]
    upem = struct.unpack(">H", data[h + 18 : h + 20])[0]
    h = tables["hhea"][0]
    asc, desc = struct.unpack(">hh", data[h + 4 : h + 8])
    return 0.5 + (asc + desc) / (2 * upem)


def runs(n):
    rs = n.get("meta", {}).get("ux", {}).get("rangedStyles", [])
    out = []
    start = 0
    for s in rs:
        end = start + s.get("length", 0)
        out.append(
            {
                "from": start,
                "to": end,
                "weight": weights.get(s.get("fontStyle"), 400),
                "size": s.get("fontSize", 16),
                "color": col(s.get("fill", {})),
                "uppercase": s.get("textTransform") == "uppercase",
                "underline": s.get("underline", False),
            }
        )
        start = end
    return out


pages = []
report = []
slugs = {
    "Home": "home",
    "Blog": "blog",
    "Blog Detail": "blog-detail",
    "Case Study": "case-studies",
    "Case Study Detail": "case-study-detail",
    "About": "about",
    "Services-Landing": "services",
    "Services-Individual": "search-engine-marketing",
}
for bid, b in r["artboards"].items():
    slug = slugs[b["name"]]
    d = json.loads(
        (SRC / f"artwork/artboard-{bid}/graphics/graphicContent.agc").read_text()
    )
    defs = []
    graphics = []
    header_graphics = []
    backgrounds = []
    texts = []
    images = []
    count = [0]
    bg = col(
        d["children"][0]
        .get("style", {})
        .get("fill", {})
        .get("color", {"value": {"r": 255, "g": 255, "b": 255}})
    )
    foot = [b["height"] - 679]

    def walk(raw, parent=I, clips=(), scope="page", anc=(), opacity=1):
        n = resolve(raw)
        if (
            not n
            or n.get("visible") is False
            or n.get("style", {}).get("opacity", 1) == 0
        ):
            return
        st = n.get("style", {})
        opacity *= st.get("opacity", 1)
        m = mul(parent, matrix(n.get("transform", {})))
        ux = n.get("meta", {}).get("ux", {})
        name = n.get("name", "")
        anc = anc + (name,)
        if name == "Nav":
            scope = "header"
        if name == "Footer":
            scope = "footer"
            foot[0] = m[5]
        if ux.get("clipPath"):
            cp = ux["clipPath"]
            clip = cp.get("clipPath", cp)
            count[0] += 1
            cid = "clip-" + slug + "-" + str(count[0])
            shapes = "".join(
                shape(resolve(c).get("shape", {})) for c in clip.get("children", [])
            )
            defs.append(
                f'<clipPath id="{cid}" clipPathUnits="userSpaceOnUse"><g transform="{mat(m)}">{shapes}</g></clipPath>'
            )
            clips = clips + (cid,)
        if n.get("type") == "text":
            txt = n.get("text", {}).get("rawText", "")
            f = font_style(n)
            if not txt.strip() or txt.lstrip().startswith('{"') or f["size"] < 6:
                return
            ls = []
            for para in n["text"].get("paragraphs", []):
                for line in para.get("lines", []):
                    if line:
                        entry = copy.deepcopy(line[0])
                        entry["to"] = line[-1]["to"]
                        ls.append(entry)
            frame = n["text"].get("frame", {})
            if frame.get("type") == "autoHeight":
                offset = frame.get("width", 0) * (
                    {"center": 0.5, "right": 1}.get(f["align"], 0)
                )
                for line in ls:
                    line["x"] = line.get("x", 0) + offset
            if not ls:
                return
            key = (
                "shared_" if scope != "page" else slug.replace("-", "_") + "_"
            ) + re.sub("[^a-z0-9]", "", raw.get("syncSourceGuid", n.get("id", "")))[:12]
            x = min(l.get("x", 0) for l in ls)
            y = min(l["y"] for l in ls) - f["size"] * baseline(f)
            frame = n["text"].get("frame", {})
            width = frame.get(
                "width", max((l["to"] - l["from"]) * f["size"] * 0.62 for l in ls)
            )
            height = max(l["y"] for l in ls) - y + f["size"] * 0.2
            texts.append(
                {
                    "key": key,
                    "text": txt,
                    "scope": scope,
                    "matrix": m,
                    "font": f,
                    "runs": runs(n),
                    "lines": ls,
                    "baseline": baseline(f),
                    "frame": frame,
                    "x": m[4] + x * m[0],
                    "y": m[5] + y * m[3],
                    "width": width * m[0],
                    "height": height * m[3],
                    "ancestors": list(anc),
                    "tag": "p",
                }
            )
        elif n.get("type") == "shape":
            image_key = None
            s = n.get("shape", {})
            fill = st.get("fill", {})
            stroke = st.get("stroke", {})
            body = shape(s)
            if (
                s.get("type") == "rect"
                and s.get("width", 0) * m[0] >= 1240
                and m[4] <= 20
                and s.get("height", 0) >= 100
                and fill.get("type") == "solid"
                and fill.get("color", {}).get("alpha", 1) == 1
            ):
                backgrounds.append(
                    {
                        "from": m[5],
                        "to": m[5] + s["height"] * m[3],
                        "color": col(fill["color"]),
                    }
                )
            if fill.get("type") == "pattern":
                p = fill["pattern"]
                uid = p.get("meta", {}).get("ux", {}).get("uid")
                asset = assets.get(uid)
                if asset:
                    key = (
                        ("shared_" if scope != "page" else slug.replace("-", "_") + "_")
                        + "image_"
                        + re.sub(
                            "[^a-z0-9]", "", raw.get("syncSourceGuid", n.get("id", ""))
                        )[:10]
                    )
                    image_key = key
                    images.append(
                        {
                            "key": key,
                            "asset": asset,
                            "scope": scope,
                            "label": name,
                            "x": m[4] + s.get("x", 0),
                            "y": m[5] + s.get("y", 0),
                            "width": s.get("width", 0) * m[0],
                            "height": s.get("height", 0) * m[3],
                        }
                    )
                    count[0] += 1
                    cid = "img-" + slug + "-" + str(count[0])
                    defs.append(f'<clipPath id="{cid}">{body}</clipPath>')
                    ix, iy, iw, ih = (
                        s.get("x", 0),
                        s.get("y", 0),
                        s.get("width", 0),
                        s.get("height", 0),
                    )
                    pu = p.get("meta", {}).get("ux", {})
                    aspect = (
                        "none"
                        if pu.get("scaleBehavior") == "fill"
                        else "xMidYMid slice"
                    )
                    if "scale" in pu:
                        factor = max(iw / p["width"], ih / p["height"]) * pu["scale"]
                        nw = p["width"] * factor
                        nh = p["height"] * factor
                        ix += (iw - nw) / 2 + pu.get("offsetX", 0)
                        iy += (ih - nh) / 2 + pu.get("offsetY", 0)
                        iw, ih = nw, nh
                    body = f'<image x="{ix}" y="{iy}" width="{iw}" height="{ih}" href="{{{{{key}}}}}" preserveAspectRatio="{aspect}" clip-path="url(#{cid})"/>'
            attrs = f'fill="{col(fill.get("color",{})) if fill.get("type")=="solid" else "none"}"'
            if stroke.get("type") == "solid":
                attrs += f' stroke="{col(stroke.get("color",{}))}" stroke-width="{stroke.get("width",1)}"'
            body = f'<g transform="{mat(m)}" {attrs} opacity="{opacity}">{body}</g>'
            for c in clips:
                body = f'<g clip-path="url(#{c})">{body}</g>'
            if (
                s.get("type") == "rect"
                and 80 <= s.get("width", 0) <= 350
                and 28 <= s.get("height", 0) <= 80
                and fill.get("type") == "solid"
            ):
                body = f'<g data-ui-button="true">{body}</g>'
            if image_key:
                body = f'<g data-image="{image_key}">{body}</g>'
            graphics.append(body)
            if scope == "header":
                header_graphics.append(body)
        for c in n.get("artboard", n.get("group", n)).get("children", []):
            walk(c, m, clips, scope, anc, opacity)

    walk(d, [1, 0, 0, 1, -b["x"], -b["y"]])
    for t in texts:
        if (
            t["scope"] == "page"
            and t["font"]["size"] >= 24
            and t["font"]["weight"] >= 700
        ):
            t["tag"] = "h2"
    hs = [t for t in texts if t["scope"] == "page" and t["font"]["size"] >= 40]
    if hs:
        min(hs, key=lambda t: t["y"])["tag"] = "h1"
    # Order editable content by visual reading order instead of XD layer order.
    texts.sort(key=lambda t: (round(t["y"] / 12), t["x"]))
    svg = (
        f'<svg xmlns="http://www.w3.org/2000/svg" width="1280" height="{b["height"]}" viewBox="0 0 1280 {b["height"]}" aria-hidden="true"><defs>'
        + "".join(defs)
        + "</defs>"
        + "".join(graphics)
        + "</svg>"
    )
    (ROOT / "design-source/reference-artboards").mkdir(parents=True, exist_ok=True)
    (ROOT / "design-source/reference-artboards" / f"{slug}.svg").write_text(svg)
    if slug == "search-engine-marketing":
        (THEME / "assets/brand.svg").write_text(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="100 43 291 61"><defs>'
            + "".join(defs)
            + "</defs>"
            + "".join(header_graphics)
            + "</svg>"
        )
        (THEME / "assets/favicon.svg").write_text(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="100 45 38 48"><defs>'
            + "".join(defs)
            + "</defs>"
            + "".join(header_graphics)
            + "</svg>"
        )
    out = {
        "name": b["name"],
        "slug": slug,
        "width": 1280,
        "height": b["height"],
        "background": bg,
        "footerY": foot[0],
        "backgrounds": backgrounds,
        "texts": texts,
        "images": images,
    }
    (SRC / "reference-data" / f"{slug}.json").write_text(
        json.dumps(out, ensure_ascii=False, indent=2)
    )
    pages.append({k: out[k] for k in ["name", "slug", "width", "height", "footerY"]})
    report.append(f"{slug}: {len(texts)} text fields, {len(images)} image fields")
(SRC / "reference-data/pages.json").write_text(json.dumps(pages, indent=2))
print("\n".join(report))
print("Unresolved symbols:", len(unresolved))
assert not unresolved
