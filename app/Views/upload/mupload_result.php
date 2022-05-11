<?php foreach ($file_info_array as $fileInfo) : // (2) ?> 
        <hr />
        <?php foreach ($fileInfo as $key => $val) : ?>
            <p><?= $key ?> : <?= $val ?></p>
        <?php endforeach; ?>
    <?php endforeach ?>