ТОП самых популярных постов арзамасского VK!

<?php foreach ($posts as $key => $post): ?>
<?php /* @var \App\Service\VK\Post $post */ ?>
<?php $text = mb_strlen($post->text) <= 255 ? $post->text : mb_substr($post->text, 0, 255) . '...' ?>
<?= sprintf('%d. %s - "<b>%s</b>" %s', $key + 1, $post->screen_name, $text, $post->link_vk) ?>

<?= sprintf(
    'Просмотров: %s, Лайков: %s, Репостов: %s, Комментариев: %s',
    $post->views,
    $post->likes,
    $post->reposts,
    $post->comments
) ?>


<?php endforeach; ?>
