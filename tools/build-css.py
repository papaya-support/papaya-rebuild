"""Bundle local fonts and the nested, responsive theme stylesheet. No XD coordinates."""
from pathlib import Path
import re
root=Path(__file__).resolve().parent.parent
theme=root/'wordpress/wp-content/themes/papaya-search-child'
fonts=(theme/'assets/fonts/fonts.css').read_text().replace("url('","url('fonts/").replace('url("','url("fonts/')
fonts=re.sub(r"url\((?![\"'])", "url(fonts/", fonts)
base=(root/'tools/styles/base.css').read_text()
(theme/'assets/site.css').write_text('/* Bundled by tools/build-css.py. Edit tools/styles/base.css. */\n'+fonts+'\n'+base)
print('Built responsive assets/site.css; no page geometry or inline styles.')
