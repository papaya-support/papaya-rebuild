<?php
/** Shared service FAQs; supports every question present in the imported source. */
defined('ABSPATH') || exit();
$questions = [];
foreach (ps_section_groups()['search-engine-marketing'] as $section) {
    if ($section['name'] !== 'section_faqs') {
        continue;
    }
    foreach ($section['fields'] as $key => $name) {
        if (str_starts_with($name, 'question') && ps_field_value($key) !== '') {
            $number = $name === 'question' ? 1 : (int) substr($name, 9);
            $answer_name = $number === 1 ? 'answer' : 'answer_' . $number;
            $questions[] = ['question' => $key, 'answer' => array_search($answer_name, $section['fields'], true)];
        }
    }
}
if (!$questions) {
    return;
}
$has_image = ps_value('search_engine_marketing_image_ba13013ff2') || !get_post_meta(get_the_ID(), '_ps_live_service_source', true);
?>
<section class="section-search-engine-marketing-faqs section">
    <div class="container">
        <?php ps_text('search_engine_marketing_de2d5ec0ff46', 'h2', 'section-title accent'); ?>
    </div>
    <div class="container<?php echo $has_image ? ' split' : ''; ?>">
        <?php if ($has_image): ?>
        <div class="split-media">
            <?php ps_image('search_engine_marketing_image_ba13013ff2', '', false); ?>
        </div>
        <?php endif; ?>
        <div class="split-copy">
            <?php foreach ($questions as $question): ?>
            <details class="faq-item">
                <summary data-field="<?php echo esc_attr($question['question']); ?>"><?php echo ps_inline_content($question['question']); ?></summary>
                <div class="faq-answer">
                    <?php
                    if (ps_field_value($question['answer']) === '' && !get_post_meta(get_the_ID(), '_ps_live_service_source', true)) {
                        ps_faq_answer($question['question']);
                    } else {
                        ps_text($question['answer'], 'div', 'prose');
                    }
                    ?>
                </div>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
