"""Order ACF database-install schemas by PHP section reading order; use WYSIWYG for long text."""
from pathlib import Path
import json,re
root=Path(__file__).resolve().parent.parent
theme=root/'wordpress/wp-content/themes/papaya-search-child'
path=theme/'acf-import/field-groups.json';groups=json.loads(path.read_text());orders={}
label_fixes={'home_c04f66011766':'SEO Service','home_fadcbf34d7f6':'PPC Service','home_01079a482a16':'Analytics Service','home_4dbc82586a2a':'AI SEO Service','services_cf9924922477':'Analytics Service','services_8c6eed7c6379':'WordPress Service'}
corrections={}
for group in groups:
 slug=group['key'].removeprefix('group_ps_');fields={f['name']:f for f in group['fields']};ordered=[]
 if slug=='shared':
  primary=['shared_7b3ca0dbb8c9','ps_booking_url','shared_78f8d1b3d0a8','shared_63ce494a885e','shared_4937e4e54c2c','shared_a8e4f9f45c81']
 else:
  template=(theme/'page-templates'/f'{slug}.php').read_text()
  parts=re.findall(r"get_template_part\('([^']+)'",template);primary=[]
  for part in parts:
   source=(theme/(part+'.php')).read_text()
   for name in re.findall(r'''["']([a-z][a-z0-9_]+)["']''',source):
    if name in fields and not name.endswith(('_url','_alt','_answer','_category')) and name not in primary:primary.append(name)
 for name in primary:
  for key in [name,name+'_url',name+'_alt',name+'_answer',name+'_category']:
   if key in fields and key not in ordered:ordered.append(key)
 ordered.extend(name for name in fields if name not in ordered)
 for i,name in enumerate(ordered):
  f=fields[name];f['menu_order']=i
  base=name.removesuffix('_url')
  if base in label_fixes:
   label=label_fixes[base]+' — Button '+('URL' if name.endswith('_url') else 'Label')
   if f['label']!=label:corrections[name]={'old':f['label'],'new':label}
   f['label']=label
  if f['type']=='textarea':
   f['type']='wysiwyg';f.update(tabs='all',toolbar='full',media_upload=0,delay=1)
   for setting in ['rows','new_lines','maxlength']:f.pop(setting,None)
   f['instructions']='Edit this content with the visual editor. Headings and button labels support inline formatting; descriptions support paragraphs, lists, and links.'
 group['fields']=[fields[name] for name in ordered];orders[group['key']]=ordered
path.write_text(json.dumps(groups,indent=2,ensure_ascii=False)+'\n')
(theme/'inc/acf-editor-order.php').write_text("<?php\n/** One-time editor migration order, matching the PHP sections from top to bottom. */\ndefined('ABSPATH') || exit;\nreturn [\n"+''.join("    '"+key+"' => [\n"+''.join("        '"+name+"',\n" for name in names)+"    ],\n" for key,names in orders.items())+"];\n")
print('Ordered',sum(map(len,orders.values())),'fields across',len(orders),'groups.')

if corrections:
 (theme/'inc/acf-editor-labels.php').write_text("<?php\ndefined('ABSPATH') || exit;\nreturn [\n"+''.join("    '"+name+"' => ['old'=>'"+v['old']+"','new'=>'"+v['new']+"'],\n" for name,v in corrections.items())+"];\n")
