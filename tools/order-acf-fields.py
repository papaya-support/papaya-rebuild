"""Order ACF database-install schemas by PHP section reading order; use WYSIWYG for long text."""

from pathlib import Path
import json, re

root = Path(__file__).resolve().parent.parent
theme = root / "wordpress/wp-content/themes/papaya-search-child"
path = theme / "acf-import/field-groups.json"
groups = json.loads(path.read_text())
orders = {}
home = next(g for g in groups if g["key"] == "group_ps_home")
if not any(f["name"] == "home_hero_emblem" for f in home["fields"]):
    home["fields"].append(
        dict(
            key="field_home_hero_emblem",
            name="home_hero_emblem",
            label="Hero — Emblem Image",
            type="image",
            return_format="id",
            preview_size="medium",
            library="all",
            instructions="Choose the circular emblem. Alternative text comes from the Media Library.",
        )
    )
for group in groups:
    if group["key"] == "group_ps_blog":
        group["fields"] = [
            f
            for f in group["fields"]
            if f["name"]
            in ["blog_2f0c8cf2c985", "blog_37a359563ced", "blog_image_a770b85be1"]
        ]
label_fixes = {
    "home_c04f66011766": "SEO Service",
    "home_fadcbf34d7f6": "PPC Service",
    "home_01079a482a16": "Analytics Service",
    "home_4dbc82586a2a": "AI SEO Service",
    "services_cf9924922477": "Analytics Service",
    "services_8c6eed7c6379": "WordPress Service",
}
corrections = {}
short_fields = [
    "about_0207ec398514",
    "about_0bdb2df6cee4",
    "about_3167a1bf8b73",
    "about_3fed862d01f7",
    "about_49f625079f2e",
    "about_5182134bb65f",
    "about_595ae388ed18",
    "about_5d0d58023776",
    "about_64c8a292f1a0",
    "about_718abc37e0eb",
    "about_77299c25f780",
    "about_7db8407b1d3f",
    "about_8ccc8c3f77e1",
    "about_9849908bc21e",
    "about_a48b87d433a1",
    "about_b4e4254098b9",
    "about_b5f3d5d9b13b",
    "about_b871389519ab",
    "about_bf25e9e3a036",
    "about_c3554bd72d4e",
    "about_cc11b96d229a",
    "about_da4d2d3bcd37",
    "about_dc5e50a805d4",
    "about_e90a86ecb353",
    "about_f1bcbe1f32e9",
    "blog_2f0c8cf2c985",
    "blog_detail_15daca854069",
    "blog_detail_1624e4d9861e",
    "blog_detail_2c021570ce1c",
    "blog_detail_8c780093d5a1",
    "blog_detail_8c8e30bb3e63",
    "blog_detail_9c13381244a8",
    "blog_f65cc6729076",
    "case_studies_089cedf19e41",
    "case_studies_5f8c3c80c990",
    "case_study_detail_74dd8a1b256d",
    "case_study_detail_7e19b95f2be4",
    "case_study_detail_afcd2640f663",
    "case_study_detail_dac02921ffd6",
    "case_study_detail_e27663e17aca",
    "home_01079a482a16",
    "home_09fe6c6170dc",
    "home_1d791eb121c5",
    "home_20f80fb1ab2f",
    "home_2263b6f88209",
    "home_3416c7bfdc6a",
    "home_473db858b064",
    "home_4973267b1258",
    "home_4dbc82586a2a",
    "home_536fe4a39b43",
    "home_5697044bab17",
    "home_58c8c372efdf",
    "home_6666581aab96",
    "home_685fc698bd13",
    "home_6901adf0ab1d",
    "home_7393b65da373",
    "home_840918b075ea",
    "home_8c4225c0ac7d",
    "home_95726762e92f",
    "home_9e841ae19a6e",
    "home_b48f00ea5554",
    "home_c03395a13124",
    "home_c04f66011766",
    "home_c71150b00266",
    "home_d4db261ea551",
    "home_ef6e013268e4",
    "home_fadcbf34d7f6",
    "search_engine_marketing_12d045c16ab7",
    "search_engine_marketing_1a8f0aa708c3",
    "search_engine_marketing_2263d859bf84",
    "search_engine_marketing_2a5d5198d572",
    "search_engine_marketing_49292f5e4eed",
    "search_engine_marketing_4daf58fc05bf",
    "search_engine_marketing_5da4f91cb7c5",
    "search_engine_marketing_6e5798b9f4ea",
    "search_engine_marketing_87a5e5276ed3",
    "search_engine_marketing_8f270f2a2afd",
    "search_engine_marketing_9a4e6a600a10",
    "search_engine_marketing_b1060e1729d0",
    "search_engine_marketing_bb38e3397bbb",
    "search_engine_marketing_c552a4ad539c",
    "search_engine_marketing_d7c7e6181717",
    "search_engine_marketing_de2d5ec0ff46",
    "search_engine_marketing_e4c9a55704b0",
    "search_engine_marketing_f56f157847bf",
    "search_engine_marketing_f6246eab32c5",
    "services_1d102d9577a4",
    "services_350220fa15e8",
    "services_403955cd73a3",
    "services_4b4ceb001243",
    "services_798933f3ef4e",
    "services_822294744ef5",
    "services_879ad37c29e0",
    "services_8c6eed7c6379",
    "services_8eb4c91b801a",
    "services_92fa01adc928",
    "services_94024e830fa2",
    "services_a706330f88c5",
    "services_b8a1a172da9c",
    "services_bd0b0253035b",
    "services_bed98f20a891",
    "services_c540653f6d16",
    "services_cf9924922477",
    "services_e0dbcb08a264",
    "services_fb1240267041",
    "shared_4937e4e54c2c",
    "shared_63ce494a885e",
    "shared_78f8d1b3d0a8",
    "shared_7b3ca0dbb8c9",
]
for group in groups:
    slug = group["key"].removeprefix("group_ps_")
    fields = {f["name"]: f for f in group["fields"]}
    ordered = []
    if slug == "shared":
        primary = [
            "shared_7b3ca0dbb8c9",
            "ps_booking_url",
            "shared_78f8d1b3d0a8",
            "shared_63ce494a885e",
            "shared_4937e4e54c2c",
            "shared_a8e4f9f45c81",
        ]
    else:
        # Field editor order is independent of reusable template argument order.
        # Keep the explicit visual order map, appending newly introduced fields below.
        order_source = (theme / "inc/acf-editor-order.php").read_text()
        match = re.search(
            r"'" + re.escape(group["key"]) + r"' => \[(.*?)\]", order_source, re.S
        )
        primary = re.findall(r"'([^']+)'", match.group(1)) if match else []
    for name in primary:
        for key in [
            name,
            name + "_url",
            name + "_alt",
            name + "_answer",
            name + "_category",
        ]:
            if key in fields and key not in ordered:
                ordered.append(key)
    ordered.extend(name for name in fields if name not in ordered)
    for i, name in enumerate(ordered):
        f = fields[name]
        f["menu_order"] = i
        base = name.removesuffix("_url")
        if base in label_fixes:
            label = (
                label_fixes[base]
                + " — Button "
                + ("URL" if name.endswith("_url") else "Label")
            )
            if f["label"] != label:
                corrections[name] = {"old": f["label"], "new": label}
            f["label"] = label
        if name in short_fields and f["type"] in ["text", "textarea", "wysiwyg"]:
            f["type"] = "text"
            f["instructions"] = "Enter plain text."
            for setting in [
                "tabs",
                "toolbar",
                "media_upload",
                "delay",
                "rows",
                "new_lines",
            ]:
                f.pop(setting, None)
        elif f["type"] == "textarea":
            f["type"] = "wysiwyg"
            f.update(tabs="all", toolbar="full", media_upload=0, delay=1)
            for setting in ["rows", "new_lines", "maxlength"]:
                f.pop(setting, None)
            f["instructions"] = (
                "Edit this content with the visual editor. Headings and button labels support inline formatting; descriptions support paragraphs, lists, and links."
            )
    group["fields"] = [fields[name] for name in ordered]
    orders[group["key"]] = ordered
path.write_text(json.dumps(groups, indent=2, ensure_ascii=False) + "\n")
(theme / "inc/acf-editor-order.php").write_text(
    "<?php\n/** One-time editor migration order, matching the PHP sections from top to bottom. */\ndefined('ABSPATH') || exit;\nreturn [\n"
    + "".join(
        "    '"
        + key
        + "' => [\n"
        + "".join("        '" + name + "',\n" for name in names)
        + "    ],\n"
        for key, names in orders.items()
    )
    + "];\n"
)
print(
    "Ordered", sum(map(len, orders.values())), "fields across", len(orders), "groups."
)

if corrections:
    (theme / "inc/acf-editor-labels.php").write_text(
        "<?php\ndefined('ABSPATH') || exit;\nreturn [\n"
        + "".join(
            "    '"
            + name
            + "' => ['old'=>'"
            + v["old"]
            + "','new'=>'"
            + v["new"]
            + "'],\n"
            for name, v in corrections.items()
        )
        + "];\n"
    )
