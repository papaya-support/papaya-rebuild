<?php
/** Blog, case-study and related-post cards share the same accessible link/image structure. */
defined('ABSPATH') || exit();

// Each variant owns its field mapping; the section markup remains shared.
switch ($args['variant'] ?? '') {
    case 'blog-detail-related-posts':
        $args = [
            'class' => 'section-blog-detail-related-posts section related-posts',
            'cards' => [
                [
                    'image' => 'blog_detail_image_e66cb91b30',
                    'title' => 'blog_detail_4d181476ee16',
                    'tag' => 'h3',
                    'url' => ps_route('blog-detail'),
                ],
                [
                    'image' => 'blog_detail_image_e850bb0d10',
                    'title' => 'blog_detail_8c5ed5ef437b',
                    'tag' => 'h3',
                    'url' => ps_route('blog-detail'),
                ],
                [
                    'image' => 'blog_detail_image_e6018f7e64',
                    'title' => 'blog_detail_1fab6576ba8d',
                    'tag' => 'h3',
                    'url' => ps_route('blog-detail'),
                ],
            ],
            'heading' => [
                [
                    'type' => 'text',
                    'field' => 'blog_detail_1624e4d9861e',
                    'tag' => 'h2',
                    'class' => 'section-title',
                ],
            ],
            'grid_class' => 'grid grid-three',
        ];
        break;
    case 'case-studies-cards':
        $args = [
            'class' => 'section-case-studies-cards section section-tight',
            'cards' => [
                [
                    'image' => 'case_studies_image_8dd5e5fb58',
                    'title' => 'case_studies_13234451b10b',
                    'tag' => 'h2',
                    'url' => ps_route('case-study-detail'),
                    'excerpt' => 'case_studies_aea86c447f03',
                ],
                [
                    'image' => 'case_studies_image_14cb78ca6e',
                    'title' => 'case_studies_1c7050391843',
                    'tag' => 'h2',
                    'url' => ps_route('case-study-detail'),
                    'excerpt' => 'case_studies_797e93346093',
                ],
                [
                    'image' => 'case_studies_image_5ad8dd81b9',
                    'title' => 'case_studies_0ecd54bcba1a',
                    'tag' => 'h2',
                    'url' => ps_route('case-study-detail'),
                    'excerpt' => 'case_studies_c478f4605f8c',
                ],
                [
                    'image' => 'case_studies_image_af8f78b46e',
                    'title' => 'case_studies_0a7c3278aa61',
                    'tag' => 'h2',
                    'url' => ps_route('case-study-detail'),
                    'excerpt' => 'case_studies_7ccd51309801',
                ],
                [
                    'image' => 'case_studies_image_295549ea58',
                    'title' => 'case_studies_716425e49fa2',
                    'tag' => 'h2',
                    'url' => ps_route('case-study-detail'),
                    'excerpt' => 'case_studies_4c074436f397',
                ],
                [
                    'image' => 'case_studies_image_b6a651e3c2',
                    'title' => 'case_studies_74f48e8c27a3',
                    'tag' => 'h2',
                    'url' => ps_route('case-study-detail'),
                    'excerpt' => 'case_studies_938030bd0b43',
                ],
                [
                    'image' => 'case_studies_image_540f75952e',
                    'title' => 'case_studies_304856bee0cf',
                    'tag' => 'h2',
                    'url' => ps_route('case-study-detail'),
                    'excerpt' => 'case_studies_c6058d1d5199',
                ],
                [
                    'image' => 'case_studies_image_27fdb373ca',
                    'title' => 'case_studies_79f1326abebe',
                    'tag' => 'h2',
                    'url' => ps_route('case-study-detail'),
                    'excerpt' => 'case_studies_02b4fd4b7c29',
                ],
                [
                    'image' => 'case_studies_image_340ebfd1f6',
                    'title' => 'case_studies_689120d3d2e7',
                    'tag' => 'h2',
                    'url' => ps_route('case-study-detail'),
                    'excerpt' => 'case_studies_bf246d534c46',
                ],
            ],
            'grid_class' => 'container grid grid-three post-grid',
        ];
        break;
}
?>
<section class="<?php echo esc_attr($args['class']); ?>">
    <?php if (!empty($args['heading'])): ?><div class="container">
        <?php get_template_part('template-parts/components/content-items', null, [
            'items' => $args['heading'],
        ]); ?>
        <?php endif; ?>
        <div class="<?php echo esc_attr($args['grid_class']); ?>">
            <?php foreach ($args['cards'] as $card):
                $tag = ($card['tag'] ?? '') === 'h3' ? 'h3' : 'h2'; ?>
            <article class="post-card" <?php if (
    !empty($card['category'])
): ?> data-category="<?php echo esc_attr(ps_value($card['category'], 'SEO')); ?>" <?php endif; ?>>
                <?php ps_image($card['image']); ?>
                <<?php echo $tag; ?>><a data-field="<?php echo esc_attr($card['title']); ?>" href="<?php echo esc_url(ps_value($card['title'] . '_url', '') ?: $card['url']); ?>"><?php echo ps_inline_content($card['title']); ?></a></<?php echo $tag; ?>>
                <?php if (!empty($card['excerpt'])) {
                    ps_text($card['excerpt'], 'div', 'prose ');
                } ?>
            </article>
            <?php
            endforeach; ?>
        </div>
        <?php if (!empty($args['heading'])): ?>
    </div>
    <?php endif; ?>
</section>
