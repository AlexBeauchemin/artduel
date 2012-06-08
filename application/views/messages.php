<?php
    $messages_output = $this->messages_model->getAll();
?>
<?php if($messages_output['messages_errors']) : ?>
    <div class='showErrors'>
        <ul>
        <?php foreach($messages_output['messages_errors'] as $error): ?>
            <li><?php echo $error; ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if($messages_output['messages_alerts']) : ?>
    <div class='showMessages'>
        <ul>
        <?php foreach($messages_output['messages_alerts'] as $item): ?>
            <li><?php echo $item; ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if($messages_output['messages_success']) :  ?>
    <div class='showSuccess'>
        <ul>
        <?php foreach($messages_output['messages_success'] as $item): ?>
            <li><?php echo $item; ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>