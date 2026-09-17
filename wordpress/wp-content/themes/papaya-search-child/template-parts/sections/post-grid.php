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
