"""Create the complete WordPress delivery and the installable child-theme archive."""

from pathlib import Path
import zipfile, json, hashlib

root = Path(__file__).resolve().parent.parent
out = root / "deliverables"
out.mkdir(exist_ok=True)
required = [
    "index.php",
    "wp-config.php",
    "wp-settings.php",
    "wp-load.php",
    "wp-login.php",
    "wp-admin/index.php",
    "wp-includes/version.php",
    "wp-content/themes/astra/style.css",
    "wp-content/themes/papaya-search-child/style.css",
    "wp-content/plugins/advanced-custom-fields/acf.php",
    "wp-content/mu-plugins/papaya-install.php",
]
for file in required:
    assert (root / "wordpress" / file).is_file(), file
excluded = {"node_modules", ".git", "__pycache__"}
files = []
for directory in ["wordpress", "design-source", "tools"]:
    for p in (root / directory).rglob("*"):
        if p.is_file() and not excluded.intersection(p.parts):
            files.append(p)
files += [root / "README.md", root / "DEPENDENCIES.json"]
files += list((root / "verification").glob("*.json"))
full = out / "papaya-search-complete-wordpress.zip"
with zipfile.ZipFile(full, "w", zipfile.ZIP_DEFLATED, compresslevel=6) as z:
    for p in sorted(files):
        z.write(p, p.relative_to(root))
child = root / "wordpress/wp-content/themes/papaya-search-child"
archive = out / "papaya-search-child.zip"
with zipfile.ZipFile(archive, "w", zipfile.ZIP_DEFLATED, compresslevel=6) as z:
    for p in sorted(child.rglob("*")):
        if p.is_file():
            z.write(p, Path("papaya-search-child") / p.relative_to(child))
for p in [full, archive]:
    with zipfile.ZipFile(p) as z:
        assert z.testzip() is None
    print(
        f"{p.name}: {p.stat().st_size/1024/1024:.1f} MB; SHA256 {hashlib.sha256(p.read_bytes()).hexdigest()}"
    )
(root / "verification/package-checks.json").write_text(
    json.dumps(
        {
            "required_files_present": required,
            "wordpress_file_count": sum(
                p.is_file() for p in (root / "wordpress").rglob("*")
            ),
            "full_archive_files": len(files),
            "zip_integrity": "passed",
        },
        indent=2,
    )
)
