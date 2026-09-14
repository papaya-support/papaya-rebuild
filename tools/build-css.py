"""Build the single frontend stylesheet from base rules, fonts and XD geometry.
Run after extract-xd.py whenever the design source or base styles change.
"""
import json, re
from pathlib import Path
root=Path(__file__).resolve().parent.parent
theme=root/'wordpress/wp-content/themes/papaya-search-child'
spacing=json.loads((theme/'inc/spacing.json').read_text())
rules=[]
page_rules={}
def rule(selector, properties):
    # Each page/field becomes a native CSS nesting scope. Split only the
    # generated selector lists, whose page roots have equal specificity.
    for part in selector.split(','):
        node=page_rules
        for token in part.split(' '):
            node=node.setdefault(token, {})
        node.setdefault('__declarations', []).append(properties)
def emit_nested(nodes, depth=0):
    result=[]
    for selector, children in nodes.items():
        if selector=='__declarations': continue
        indent='  '*depth
        result.append(indent+('' if depth==0 else '& ')+selector+' {')
        for declarations in children.get('__declarations', []):
            result.extend(indent+'  '+prop.strip()+';' for prop in declarations.split(';') if prop.strip())
        result.extend(emit_nested(children, depth+1))
        result.append(indent+'}')
    return result
def num(v):
    return format(v,'.12g') if isinstance(v,(float,int)) else str(v)
def px(v): return num(v)+'px'
render=(theme/'inc/render.php').read_text()
bands={slug:json.loads(items) for slug,items in re.findall(r"'([a-z-]+)'=>\s*(\[\[.*?\]\])",render)}
for path in sorted((theme/'design').glob('*.json')):
    d=json.loads(path.read_text())
    if not isinstance(d,dict): continue
    slug=d['slug']; desktop=f'.xd-viewport[data-page="{slug}"]'; mobile=f'.mobile-site[data-page="{slug}"]'
    page_rules={}
    rules.append('\n/* '+slug+' */')
    cuts=spacing.get(slug,[])
    def compact_y(y): return y-sum(max(0,min(y,b)-a) for a,b in cuts if y>a)
    def compact_matrix(t):
        m=list(t['matrix']);m[5]-=t['y']-compact_y(t['y']);return m
    rule(desktop,'--page-height:'+num(compact_y(d['height']))+';--page-bg:'+d['background'])
    rule(desktop+' .xd-stage','height:'+px(compact_y(d['height']))+';background:'+d['background'])
    rule(desktop+' .ps-footer-columns','top:'+px(compact_y(d['footerY']+123.542)))
    if slug=='services':
        for anchor,y in [('search-engine-optimization',995),('website-analytics',1490),('wordpress-maintenance',1490)]:
            rule(desktop+' #'+anchor,'top:'+px(compact_y(y)))
    for t in d['texts']:
        f=t['font']; field=f'[data-field="{t["key"]}"]'; sel=desktop+' '+field; msel=mobile+' '+field
        css='--text-anchor:'+f['align']+';font-family:'+f['family']+';font-weight:'+num(f['weight'])+';font-size:'+px(f['size'])+';color:'+f['color']+';letter-spacing:'+px(f['spacing'])
        if f['uppercase']: css+=';text-transform:uppercase'
        rule(sel+','+msel,css)
        rule(sel,'transform:matrix('+','.join(map(num,compact_matrix(t)))+')')
        for i,r in enumerate(t['runs']):
            css='font-weight:'+num(r['weight'])+';color:'+r['color']
            if r['uppercase']: css+=';text-transform:uppercase'
            if r['underline']: css+=';text-decoration:underline'
            rule(sel+' .xd-run-'+str(i),css)
        for i,line in enumerate(t['lines']):
            rule(sel+' .xd-line-'+str(i),'left:'+px(line.get('x',0))+';top:'+px(line['y']-f['size']*t['baseline']))
        frame=t['frame'];width=frame.get('width',max(100,t['width']));x=-width/2 if frame['type']=='positioned' and f['align']=='center' else 0
        rule(sel+' .xd-edited','left:'+px(x)+';top:'+px(t['lines'][0]['y']-f['size']*t['baseline'])+';width:'+px(width)+';text-align:'+f['align']+';line-height:'+px(f['lineHeight']))
        if slug=='search-engine-marketing' and 4100<t['y']<4750 and '?' in t['text']:
            rule(desktop+' #answer-'+t['key'],'left:648px;top:'+px(compact_y(t['y']+t['height']+12))+';width:505px')
    for start,end,columns in bands[slug]:
        mid=(start+min(end,d['footerY']))/2;bg=d['background']
        for band in d.get('backgrounds',[]):
            if band['from']<=mid<band['to']:bg=band['color']
        rule(mobile+f' .mobile-band[data-band="{start}"]','background:'+bg)
    for im in d['images']:
        rule(mobile+f' .mobile-image[data-image="{im["key"]}"]','aspect-ratio:'+num(im['width'])+'/'+num(im['height']))
    if slug=='blog':
        titles=[t for t in d['texts'] if t['scope']=='page' and t['text'].startswith('Lorem ipsum') and t['font']['weight']>=700]
        for t in titles:
            fields=[t]+[e for e in d['texts'] if e['text'].startswith('Excepteur') and abs(e['x']-t['x'])<20 and t['y']<e['y']<t['y']+180]
            images=[im for im in d['images'] if abs(im['x']-t['x'])<20 and t['y']-400<im['y']<t['y']]
            for i,slot in enumerate(titles):
                delta='translate('+px(slot['x']-t['x'])+','+px(slot['y']-t['y'])+')'
                for e in fields:
                    text_delta='translate('+px(slot['x']-t['x'])+','+px(compact_y(slot['y'])-compact_y(t['y']))+')'
                    rule(desktop+f' [data-field="{e["key"]}"][data-slot="{i}"]','transform:'+text_delta+' matrix('+','.join(map(num,compact_matrix(e)))+')')
                for im in images:rule(desktop+f' [data-image="{im["key"]}"][data-slot="{i}"]','transform:'+delta)
    rules.extend(emit_nested(page_rules))
fonts=(theme/'assets/fonts/fonts.css').read_text().replace("url('","url('fonts/").replace('url("','url("fonts/')
fonts=re.sub(r"url\((?![\"'])", "url(fonts/", fonts)
base=(root/'tools/styles/base.css').read_text()
(theme/'assets/site.css').write_text('/* Generated by tools/build-css.py. Edit tools/styles/base.css for shared styles. */\n'+fonts+'\n'+base+'\n'+'\n'.join(rules)+'\n')
print('Built assets/site.css with native CSS nesting')
