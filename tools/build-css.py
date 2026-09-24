"""Compile Tailwind and the nested XD theme rules into one local-font stylesheet."""

from pathlib import Path
import re
import subprocess
import tempfile

root = Path(__file__).resolve().parent.parent
theme = root / "wordpress/wp-content/themes/papaya-search-child"
fonts = (
    (theme / "assets/fonts/fonts.css")
    .read_text()
    .replace("url('", "url('fonts/")
    .replace('url("', 'url("fonts/')
)
fonts = re.sub(r"url\((?![\"'])", "url(fonts/", fonts)
# Tailwind is compiled locally; deployed WordPress needs no Node.js runtime.
cli = root / "tools/node_modules/@tailwindcss/cli/dist/index.mjs"
if not cli.is_file():
    raise SystemExit("Missing Tailwind dependencies. Run npm ci --prefix tools first.")
with tempfile.TemporaryDirectory(prefix="papaya-tailwind-") as temporary:
    output = Path(temporary) / "compiled.css"
    subprocess.run(
        [
            "node",
            str(cli),
            "--input",
            str(root / "tools/styles/tailwind.css"),
            "--output",
            str(output),
        ],
        cwd=root / "tools",
        check=True,
    )
    base = output.read_text()
(theme / "assets/site.css").write_text(
    "/* Bundled by tools/build-css.py. Edit tools/styles/base.css and tailwind.css. */\n"
    + fonts
    + "\n"
    + base
)
print("Built Tailwind-powered assets/site.css; XD styles preserved without Preflight.")
