<ul class="breadcrumb">
    <li><?php echo $this->Html->link(__('Translations'), array('plugin' => 'i18n', 'controller' => 'translations', 'action' => 'index'), array('escape' => false, 'data-pjax' => '#page #content')); ?></li>
    <li class="active">
        <i class="icon-edit"></i> <?php echo $translation['Translation']['msgid']?>
    </li>
</ul>

<?php echo $this->Form->create('Translation', array('role' => 'form', 'data-pjax' => '#page #content'));?>        
    <?php echo $this->Form->input('id');?>
    
    <?php echo $this->Form->input('msgstr', array('escape' => false, 'div' => array('class' => 'form-group'), 'label' => __('Translation'), 'class' => 'form-control', 'placeholder' => __('Translation')));?>
    <?php echo $this->Form->input('language_id', array('escape' => false, 'div' => array('class' => 'form-group'), 'label' => __('Language'), 'class' => 'form-control', 'options' => $languages, 'placeholder' => __('Language')));?>
<?php echo $this->Form->end(array('div' => array('class' => 'text align right'), 'label' => __('Save'), 'class' => 'btn btn-success'));?>
