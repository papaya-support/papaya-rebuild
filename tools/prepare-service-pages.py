"""Map a public WordPress REST Services scan into existing Services-Individual ACF Groups.
Run with BeautifulSoup on PYTHONPATH; scan files live in ignored .local/service-scan.
"""
from pathlib import Path
from bs4 import BeautifulSoup
import json, re, html, hashlib, subprocess, concurrent.futures
from urllib.parse import urlparse
ROOT = Path(__file__).resolve().parent.parent
SCAN = ROOT / '.local/service-scan'
THEME = ROOT / 'wordpress/wp-content/themes/papaya-search-child'
ASSETS = THEME / 'import-data/services-media'
ASSETS.mkdir(parents=True, exist_ok=True)
media = {}

def clean(markup):
    soup = BeautifulSoup(str(markup), 'html.parser')
    for el in soup(['script','style','svg','noscript']): el.decompose()
    for el in list(soup.find_all(True)):
        if el.name not in ['p','br','strong','b','em','i','u','a','ul','ol','li','h2','h3','h4','h5','h6','img','blockquote','cite','table','thead','tbody','tr','th','td','figure','figcaption']:
            el.unwrap(); continue
        el.attrs = {k:v for k,v in el.attrs.items() if k in ['href','src','alt','colspan','rowspan']}
        if el.name=='img':
            url=el.get('src','')
            if not url.startswith('https://papayasearch.com/'): el.decompose();continue
            media[url] = {'file':hashlib.sha256(url.encode()).hexdigest()[:16]+'-'+Path(urlparse(url).path).name,'alt':el.get('alt','')}
        if el.name=='a' and not el.get('href'):el.unwrap()
    return str(soup).strip()

def widgets(section, source):
    out=[]; faqs=[]
    for w in section.select('[data-widget_type]'):
        kind=w['data-widget_type'].split('.')[0]
        if kind=='image':
            img=w.find('img')
            if not img:continue
            src=img.get('src','')
            if 'elementor-absolute' in w.get('class',[]) or re.search(r'(seed|leaf|element[_-]|section\d|cta.image)',src,re.I):continue
            out.append(clean(img))
        elif kind=='heading':
            h=w.select_one('.elementor-heading-title')
            if h:
                tag=h.name if re.match('^h[1-6]$',h.name) else 'h3'
                out.append('<'+tag+'>'+html.escape(h.get_text(' ',strip=True))+'</'+tag+'>')
        elif kind=='text-editor':
            c=w.select_one('.elementor-widget-container')
            out.append(clean(c or w))
        elif kind=='button':
            a=w.select_one('a.elementor-button')
            if a:out.append('<p><a class="button button-peach" href="'+html.escape(a.get('href',''),quote=True)+'">'+html.escape(a.get_text(' ',strip=True))+'</a></p>')
        elif kind=='icon-list':
            lis=w.select('.elementor-icon-list-item');items=[]
            for li in lis:
                a=li.find('a');text=html.escape(li.get_text(' ',strip=True))
                items.append('<li>'+('<a href="'+html.escape(a['href'],quote=True)+'">'+text+'</a>' if a and a.get('href') else text)+'</li>')
            out.append('<ul>'+''.join(items)+'</ul>')
        elif kind in ['toggle','accordion']:
            for item in w.select('.elementor-toggle-item, .elementor-accordion-item'):
                q=item.select_one('.elementor-toggle-title, .elementor-accordion-title');a=item.select_one('.elementor-tab-content')
                if q and a:faqs.append({'question':q.get_text(' ',strip=True),'answer':clean(a)})
        elif kind=='testimonial-carousel':
            for t in w.select('.elementor-testimonial'):
                quote=t.select_one('.elementor-testimonial__text');cite=t.select_one('cite')
                if quote:out.append('<blockquote><p>'+html.escape(quote.get_text(' ',strip=True))+'</p><cite>'+html.escape(cite.get_text(' ',strip=True) if cite else '')+'</cite></blockquote>')
        elif kind=='uael-gf-styler':
            form=w.find('form');submit=w.select_one('[type=submit]')
            label=submit.get('value','Download the eBook') if submit else 'Download the eBook'
            out.append('<p><a class="button button-peach" href="'+source+'#'+(form.get('id','') if form else '')+'">'+html.escape(label)+'</a></p>')
        elif kind!='icon':raise RuntimeError('Unhandled widget '+kind)
    return out,faqs

def split_section(parts):
    title='';image='';body=[]
    for part in parts:
        if not title and re.match(r'<h[12]>',part):
            title=BeautifulSoup(part,'html.parser').get_text(' ',strip=True);continue
        if not image and part.startswith('<img '):
            image=BeautifulSoup(part,'html.parser').img['src'];continue
        body.append(part.replace('<h1>','<h2>').replace('</h1>','</h2>'))
    return {'heading':title,'description':'\n'.join(body),'image':image}

pages=[]
for name in ['ai','seo','audit','local-seo','gbp-optmization','gbp-reinstatement','search-engine-marketing','website-analytics','website-maintenance']:
    page=json.loads((SCAN/(name+'.json')).read_text())[0]
    soup=BeautifulSoup(page['content']['rendered'],'html.parser');root=soup.find(attrs={'data-elementor-type':'wp-page'})
    sections=[];faq=[];faq_title=''
    for element in root.find_all(recursive=False):
        parts,questions=widgets(element,page['link'])
        if questions:
            faq.extend(questions)
            for part in parts:
                if part.startswith(('<h2>','<h3>')):faq_title=BeautifulSoup(part,'html.parser').get_text(' ',strip=True)
            continue
        if parts:sections.append(split_section(parts))
    intro=sections.pop(0)
    groups={'section_introduction':{'heading':intro['heading'],'subtitle':'','description':intro['description']}, 'section_banner':{'image':intro['image']}}
    roles=['section_visibility','section_ppc_services','section_benefits','section_google_ads','section_microsoft_ads']
    for i,sec in enumerate(sections):
        role=roles[min(i,len(roles)-1)]
        if role=='section_benefits' and sec['image']:
            sec['description']='<img src="'+sec['image']+'" alt="'+html.escape(media[sec['image']]['alt'],quote=True)+'">\n'+sec['description'];sec.pop('image')
        if role=='section_ppc_services':
            sec['description']=('<h2>'+html.escape(sec['heading'])+'</h2>\n' if sec['heading'] else '')+sec['description'];sec.pop('heading')
        if role=='section_microsoft_ads':sec={'heading':sec['heading'],'description_2':sec['description'],'image':sec['image']}
        if role in groups:
            # Preserve extra source sections in the final rich-text area in source order.
            key='description_2';groups[role][key]+='\n<h2>'+html.escape(sec['heading'])+'</h2>\n'+sec[key]
            if sec['image']:groups[role][key]+='\n<img src="'+sec['image']+'" alt="'+html.escape(media[sec['image']]['alt'],quote=True)+'">'
        else:groups[role]=sec
    groups['section_faqs']={'heading':faq_title or ('Frequently Asked Questions' if faq else '')}
    pages.append({'title':html.unescape(page['title']['rendered']),'path':urlparse(page['link']).path.strip('/'),'source':page['link'],'groups':groups,'faqs':faq})

def export(value,level=0):
    if isinstance(value,dict):return '[\n'+''.join('    '*(level+1)+export(k)+' => '+export(v,level+1)+',\n' for k,v in value.items())+'    '*level+']'
    if isinstance(value,list):return '[\n'+''.join('    '*(level+1)+export(v,level+1)+',\n' for v in value)+'    '*level+']'
    return "'"+str(value).replace('\\','\\\\').replace("'","\\'")+"'"

def download(item):
    url,asset=item;p=ASSETS/asset['file']
    if not p.exists():subprocess.run(['curl','-sSL','--fail','--retry','2','--max-time','60',url,'-o',str(p)],check=True)
with concurrent.futures.ThreadPoolExecutor(max_workers=5) as pool:list(pool.map(download,media.items()))
(THEME/'import-data/service-pages.php').write_text("<?php\n/** Public Services content captured 2026-10-09; editable copies are stored in ACF. */\ndefined('ABSPATH') || exit();\nreturn "+export({'pages':pages,'media':media})+';\n')
for p in pages:print(p['path'], 'groups',list(p['groups']), 'FAQs',len(p['faqs']))
print('Media:',len(media))
