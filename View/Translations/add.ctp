<ul class="breadcrumb">
    <li><?php echo $this->Html->link(__('Translations'), array('plugin' => 'i18n', 'controller' => 'translations', 'action' => 'index'), array('escape' => false, 'data-pjax' => '#page #content')); ?></li>
    <li class="active">
        <span class="label label-warning"><i class="icon-plus"></i> <?php echo __('new sentence or word'); ?></span>
    </li>
</ul>

<?php echo $this->Form->create('Translation', array('role' => 'form', 'data-pjax' => '#page #content'));?>        
    <?php echo $this->Form->input('id');?>
    
    <?php echo $this->Form->input('msgid', array('escape' => false, 'div' => array('class' => 'form-group'), 'label' => __('Sentence or word'), 'class' => 'form-control', 'rows' => 5, 'placeholder' => __('Sentence or word')));?>
<?php echo $this->Form->end(array('div' => array('class' => 'text align right'), 'label' => __('Save'), 'class' => 'btn btn-success'));?>
