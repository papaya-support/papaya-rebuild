<?php
/** Shared service FAQs: ACF content, optional answers and layout. */
defined('ABSPATH') || exit();
$faqs = function_exists('get_field') ? (get_field('section_faqs', get_the_ID(), false) ?: []) : [];
$heading = ps_section_value($faqs, 'heading', 'search_engine_marketing_de2d5ec0ff46');
$is_imported_service = (bool) get_post_meta(get_the_ID(), '_ps_live_service_source', true);
$questions = [
    [
        'question_key' => 'search_engine_marketing_e4c9a55704b0',
        'answer_key' => 'search_engine_marketing_e4c9a55704b0_answer',
        'question' => ps_section_value($faqs, 'question', 'search_engine_marketing_e4c9a55704b0'),
        'answer' => ps_section_value($faqs, 'answer', 'search_engine_marketing_e4c9a55704b0_answer'),
    ],
    [
        'question_key' => 'search_engine_marketing_1a8f0aa708c3',
        'answer_key' => 'search_engine_marketing_1a8f0aa708c3_answer',
        'question' => ps_section_value($faqs, 'question_2', 'search_engine_marketing_1a8f0aa708c3'),
        'answer' => ps_section_value($faqs, 'answer_2', 'search_engine_marketing_1a8f0aa708c3_answer'),
    ],
    [
        'question_key' => 'search_engine_marketing_bb38e3397bbb',
        'answer_key' => 'search_engine_marketing_bb38e3397bbb_answer',
        'question' => ps_section_value($faqs, 'question_3', 'search_engine_marketing_bb38e3397bbb'),
        'answer' => ps_section_value($faqs, 'answer_3', 'search_engine_marketing_bb38e3397bbb_answer'),
    ],
    [
        'question_key' => 'search_engine_marketing_12d045c16ab7',
        'answer_key' => 'search_engine_marketing_12d045c16ab7_answer',
        'question' => ps_section_value($faqs, 'question_4', 'search_engine_marketing_12d045c16ab7'),
        'answer' => ps_section_value($faqs, 'answer_4', 'search_engine_marketing_12d045c16ab7_answer'),
    ],
    [
        'question_key' => 'search_engine_marketing_49292f5e4eed',
        'answer_key' => 'search_engine_marketing_49292f5e4eed_answer',
        'question' => ps_section_value($faqs, 'question_5', 'search_engine_marketing_49292f5e4eed'),
        'answer' => ps_section_value($faqs, 'answer_5', 'search_engine_marketing_49292f5e4eed_answer'),
    ],
    [
        'question_key' => 'search_engine_marketing_6e5798b9f4ea',
        'answer_key' => 'search_engine_marketing_6e5798b9f4ea_answer',
        'question' => ps_section_value($faqs, 'question_6', 'search_engine_marketing_6e5798b9f4ea'),
        'answer' => ps_section_value($faqs, 'answer_6', 'search_engine_marketing_6e5798b9f4ea_answer'),
    ],
    [
        'question_key' => 'search_engine_marketing_faq_question_7',
        'answer_key' => 'search_engine_marketing_faq_answer_7',
        'question' => ps_section_value($faqs, 'question_7', 'search_engine_marketing_faq_question_7'),
        'answer' => ps_section_value($faqs, 'answer_7', 'search_engine_marketing_faq_answer_7'),
    ],
    [
        'question_key' => 'search_engine_marketing_faq_question_8',
        'answer_key' => 'search_engine_marketing_faq_answer_8',
        'question' => ps_section_value($faqs, 'question_8', 'search_engine_marketing_faq_question_8'),
        'answer' => ps_section_value($faqs, 'answer_8', 'search_engine_marketing_faq_answer_8'),
    ],
    [
        'question_key' => 'search_engine_marketing_faq_question_9',
        'answer_key' => 'search_engine_marketing_faq_answer_9',
        'question' => ps_section_value($faqs, 'question_9', 'search_engine_marketing_faq_question_9'),
        'answer' => ps_section_value($faqs, 'answer_9', 'search_engine_marketing_faq_answer_9'),
    ],
    ];
$questions = array_filter($questions, fn($question) => $question['question'] !== '');
if (!$questions) {
    return;
}
?>
<!-- Service FAQs -->
<section class="section-search-engine-marketing-faqs section">
    <div class="container">
        <?php if ($heading !== ''): ?>
            <h2 class="section-title accent is-uppercase" data-field="search_engine_marketing_de2d5ec0ff46"><?php echo ps_inline_content('search_engine_marketing_de2d5ec0ff46', $heading); ?></h2>
        <?php endif; ?>
    </div>
    <div class="container faq-full-width">
        <div class="faq-list">
            <?php foreach ($questions as $question): ?>
                <details class="faq-item">
                    <summary data-field="<?php echo esc_attr($question['question_key']); ?>"><?php echo ps_inline_content($question['question_key'], $question['question']); ?></summary>
                    <div class="faq-answer">
                        <?php if ($question['answer'] === '' && !$is_imported_service): ?>
                            <?php ps_faq_answer($question['question_key']); ?>
                        <?php elseif ($question['answer'] !== ''): ?>
                            <div class="prose" data-field="<?php echo esc_attr($question['answer_key']); ?>"><?php echo wp_kses_post(ps_content_html($question['answer_key'], $question['answer'])); ?></div>
                        <?php endif; ?>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
