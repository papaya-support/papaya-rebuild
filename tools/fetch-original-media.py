"""Download the source export's originals and declared image sizes without changing bytes."""
import concurrent.futures, hashlib, json, re, sys, urllib.request
import xml.etree.ElementTree as ET
from pathlib import Path
from urllib.parse import urlsplit
source = Path(sys.argv[1])
root = Path(__file__).resolve().parent.parent / '.local/original-posts/media'
ns = {'wp': 'http://wordpress.org/export/1.2/'}
urls = set()
for item in ET.parse(source).findall('./channel/item'):
    if item.findtext('wp:post_type', namespaces=ns) != 'attachment':
        continue
    url = item.findtext('wp:attachment_url', namespaces=ns)
    urls.add(url)
    for meta in item.findall('wp:postmeta', ns):
        if meta.findtext('wp:meta_key', namespaces=ns) in ('_wp_attachment_metadata', '_wp_attachment_backup_sizes'):
            for name in re.findall(r's:(?:4:"file"|14:"original_image");s:\d+:"([^"]+)"', meta.findtext('wp:meta_value', default='', namespaces=ns)):
                urls.add(url.rsplit('/', 1)[0] + '/' + name.rsplit('/', 1)[-1])
def fetch(url):
    relative = urlsplit(url).path.split('/wp-content/uploads/', 1)[1]
    target = root / relative
    target.parent.mkdir(parents=True, exist_ok=True)
    try:
        if not target.exists():
            request = urllib.request.Request(url, headers={'User-Agent': 'Papaya content migration'})
            with urllib.request.urlopen(request, timeout=60) as response:
                data = response.read()
            target.write_bytes(data)
        return {'url': url, 'bytes': target.stat().st_size, 'sha256': hashlib.sha256(target.read_bytes()).hexdigest()}
    except Exception as error:
        return {'url': url, 'error': str(error)}
with concurrent.futures.ThreadPoolExecutor(max_workers=10) as pool:
    results = list(pool.map(fetch, sorted(urls)))
report = root.parent / 'media-report.json'
report.write_text(json.dumps(results, indent=2))
errors = [r for r in results if 'error' in r]
print(f'{len(results)-len(errors)}/{len(results)} files downloaded; {sum(r.get("bytes",0) for r in results)/1048576:.1f} MiB')
for error in errors: print(error)
