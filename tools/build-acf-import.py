"""Build a one-time ACF Tools import; runtime fields live in WordPress's database."""
import json,re
from pathlib import Path
root=Path(__file__).resolve().parent.parent
theme=root/'wordpress/wp-content/themes/papaya-search-child'
labels={
'home': '''Hero — Heading|Introduction — Heading|Search Strategy — Heading|Search Strategy — Benefits|Search Strategy — Button Label|Services — Section Heading|SEO Service — Title|PPC Service — Title|Analytics Service — Title|AI SEO Service — Title|SEO Service — Description|PPC Service — Description|Analytics Service — Description|AI SEO Service — Description|SEO Service — Button Label|PPC Service — Button Label|Analytics Service — Button Label|AI SEO Service — Button Label|Search Trends — Heading|Search Trends — Description|Future-proof Strategy — Heading|Future-proof Strategy — Description|Future-proof Strategy — Call to Action Heading|Future-proof Strategy — Button Label|Testimonial — Quote Mark|Testimonial — Quote|Testimonial — Attribution|Experience — Heading|Experience — Years Statistic|Business Growth — Years Statistic|Client Count — Statistic|Experience — Statistic Description|Business Growth — Statistic Description|Client Count — Statistic Description|Results — Description|Results — Link Introduction|Results — Button Label|Closing Call to Action — Heading|Closing Call to Action — Benefits|Closing Call to Action — Button Label''',
'about': '''Hero — Heading|Introduction — Team Overview|Introduction — Team Mission|Audience Strategy — Heading|Audience Strategy — Description|Partnership — Heading|Partnership — Approach|Partnership — Collaboration|Process — Heading|Process — Introduction|Process Step 2 — Number|Process Step 1 — Number|Process Step 2 — Title|Process Step 1 — Title|Process Step 1 — Description|Process Step 2 — Description|Process Step 3 — Number|Process Step 4 — Number|Process Step 3 — Title|Process Step 4 — Title|Process Step 3 — Description|Process Step 4 — Description|Process — Button Label|Team — Heading|Careers — Heading|Team Member 1 — Name|Team Member 2 — Name|Team Member 1 — Job Title|Team Member 2 — Job Title|Team Member 1 — Biography|Team Member 2 — Biography|Careers — Description|Careers — Button Label|Team Member 1 — Button Label|Team Member 2 — Button Label|Brand Story — Heading|Brand Story — Description|Closing Call to Action — Heading|Closing Call to Action — Description|Closing Call to Action — Button Label''',
'services': '''Hero — Heading|Introduction — Heading|Introduction — Description|SEO Service — Title|SEM Service — Title|SEO Service — Subtitle|SEM Service — Subtitle|SEO Service — Description|SEM Service — Description|SEO Service — Button Label|SEM Service — Button Label|Analytics Service — Title|WordPress Service — Title|WordPress Service — Subtitle|Analytics Service — Subtitle|WordPress Service — Description|Analytics Service — Description|WordPress Service — Button Label|Analytics Service — Button Label|Closing Call to Action — Heading|Benefit 1 — Text|Benefit 2 — Text|Benefit 3 — Text|Closing Call to Action — Button Label''',
'search-engine-marketing': '''Hero — Heading|Introduction — Subtitle|Introduction — Description|Visibility — Heading|Visibility — Benefits|PPC Services — Description|SEM Benefits — Heading|SEM Benefits — Introduction|Benefit 1 — Statistic|Benefit 2 — Title|Benefit 3 — Title|Benefit 1 — Description|Benefit 2 — Description|Benefit 3 — Description|SEM Benefits — Button Label|Google Ads — Heading|Google Ads — Description|Google Ads — Partner Credentials|Microsoft Advertising — Heading|Microsoft Advertising — Description|Microsoft Advertising — Partner Credentials|FAQs — Heading|FAQ 1 — Question|FAQ 2 — Question|FAQ 3 — Question|FAQ 4 — Question|FAQ 5 — Question|FAQ 6 — Question|Closing Call to Action — Heading|Closing Call to Action — Description|Closing Call to Action — Button Label''',
'blog-detail': '''Article — Breadcrumb|Article — Title|Article — Introduction|Local SEO — Section Heading|Local SEO — Section Body|Google Map Pack — Section Heading|Google Map Pack — Section Body|Google Map Pack — Pro Tip|Local Rankings — Section Heading|Local Rankings — Section Body|Related Posts — Heading|Related Post 1 — Title|Related Post 2 — Title|Related Post 3 — Title''',
'case-study-detail': '''Case Study — Breadcrumb|Case Study — Title|Case Study — Introduction|Result 1 — Search Visibility|Result 2 — Lead Generation|Result 3 — Local Engagement|Results — Summary|Testimonial — Quote Mark|Testimonial — Quote|Testimonial — Attribution|Challenge — Heading|Challenge — Description|Challenge — Follow-up Description''',
}
image_labels={
'home':['Hero — Background Image','Search Strategy — Image','Future-proof Strategy — Image','Search Trends — Image','Closing Call to Action — Image'],
'about':['Hero — Image','Audience Strategy — Image','Partnership — Image'],
'services':['Hero — Image'],
'search-engine-marketing':['Hero — Image','Visibility — Image','PPC Services — Image','Google Ads — Certification Image','Microsoft Advertising — Certification Image','FAQs — Image'],
'blog-detail':['Article — Featured Image','Local Rankings — Diagram','Related Post 1 — Image','Related Post 2 — Image','Related Post 3 — Image'],
'case-study-detail':['Case Study — Featured Image','Challenge — Image','Results — Search Performance Chart'],
}
names={'home':'Home','about':'About','services':'Services','search-engine-marketing':'Search Engine Marketing','blog':'Blog','blog-detail':'Blog Article','case-studies':'Case Studies','case-study-detail':'Case Study Detail'}
shared_labels={'shared_78f8d1b3d0a8':'Footer — Navigation Heading','shared_63ce494a885e':'Footer — Services Heading','shared_4937e4e54c2c':'Footer — Contact Heading','shared_7b3ca0dbb8c9':'Footer — Call Button Label','shared_a8e4f9f45c81':'Footer — Copyright and Legal Text'}
groups=[];shared={}
def field(key,label,kind='textarea',**extra):
 return dict(key='field_'+key,name=key,label=label,type=kind,**extra)
def link_field(t,slug):
 text=t['text'].strip()
 return bool(re.match(r'^(Get Started|Schedule|Take the first)',text,re.I) or text in ['Case Studies','Careers','Read More','Learn More'] or (text.startswith('Lorem ipsum') and t['font']['weight']>=700))
for slug,name in names.items():
 d=json.loads((theme/'design'/f'{slug}.json').read_text());texts=[t for t in d['texts'] if t['scope']=='page'];fs=[]
 if slug in labels:
  page_labels=labels[slug].split('|');assert len(page_labels)==len(texts),(slug,len(page_labels),len(texts))
 else:
  titles=[t for t in texts if t['text'].startswith('Lorem ipsum')]
  page_labels=[]
  for t in texts:
   if t in titles:label=f'Card {titles.index(t)+1} — Title'
   elif t['text'].startswith('Excepteur'):
    title=min(titles,key=lambda a:abs(a['x']-t['x'])+abs(a['y']-t['y']))
    label=f'Card {titles.index(title)+1} — Excerpt'
   elif t['tag']=='h1':label='Introduction — Heading'
   elif slug=='blog' and 600<t['y']<750:label=f'Filters — {t["text"].strip()} Label'
   elif t['text']=='View More':label='Pagination — Button Label'
   elif t['text'].startswith('Schedule'):label='Introduction — Button Label'
   elif t['y']<350:label='Introduction — Description'
   else:label='Introduction — Consultation Text' if slug=='case-studies' else 'Introduction — Description'
   page_labels.append(label)
 for t,label in zip(texts,page_labels):
  fs.append(field(t['key'],label,rows=max(2,min(8,len(t['lines']))),new_lines='',instructions='Edit the page text here. Keep deliberate line breaks for the designed layout.'))
  if slug=='blog' and t['text'].startswith('Lorem ipsum') and t['font']['weight']>=700:
   fs.append(field(t['key']+'_category',label.replace('Title','Category'),'select',choices={v:v for v in ['Digital Marketing','SEO','SEM','Wordpress','Papaya HQ']},default_value='SEO'))
  if link_field(t,slug):fs.append(field(t['key']+'_url',label.replace('Button Label','Button URL')+' — Link' if 'Button Label' not in label else label.replace('Button Label','Button URL'),'text',instructions='Full URL, relative site URL, or tel: link. Leave blank to use the default destination.'))
  if slug=='search-engine-marketing' and 4100<t['y']<4750 and '?' in t['text']:fs.append(field(t['key']+'_answer',label.replace('Question','Answer'),rows=4))
 for i,im in enumerate(d['images']):
  label=image_labels[slug][i] if slug in image_labels else ('Hero — Image' if slug=='blog' and i==0 else f'Card {i if slug=="blog" else i+1} — Image')
  fs.append(field(im['key'],label,'image',return_format='id',preview_size='medium',library='all',instructions='Choose a replacement image. The original design asset is used when empty.'))
  fs.append(field(im['key']+'_alt',label.replace('Image','Image Alternative Text') if 'Image' in label else label+' — Alternative Text','text'))
 groups.append(dict(key='group_ps_'+slug,title=name+' — Page Content',fields=fs,location=[[dict(param='page_template',operator='==',value='page-templates/'+slug+'.php')]],position='normal',style='default',active=True,description='Editable page fields. Keep field names and keys unchanged to preserve template connections.'))
 for t in d['texts']:
  if t['key'] in shared_labels:shared[t['key']]=field(t['key'],shared_labels[t['key']],rows=2,new_lines='')
shared['booking']=field('ps_booking_url','Default Page Button and Footer Call URL','text',default_value='tel:+14044259775')
groups.append(dict(key='group_ps_shared',title='Site Content — Footer and Default Links',fields=list(shared.values()),location=[[dict(param='post_type',operator='==',value='ps_site_content')]],active=True,description='Navigation menus are managed under Appearance → Menus.'))
(theme/'acf-import/field-groups.json').write_text(json.dumps(groups,indent=2,ensure_ascii=False)+'\n')
print('Created',len(groups),'editable ACF groups with',sum(len(g['fields']) for g in groups),'fields')
