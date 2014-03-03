<?php echo $this->Form->create('Search', array('url' => array('plugin' => 'i18n', 'controller' => 'translations', 'action' => 'index'), 'data-pjax' => '#page #content')); ?>
    <?php echo $this->Form->input('key', array('type' => 'hidden', 'value' => 'Translation')); ?>
    <table class="table table-bordered table-condensed table-striped table-hover">
        <caption>
            <div class="row">
                <?php echo $this->Form->input('Translation.msgid', array('type' => 'text', 'escape' => false, 'div' => 'col-xs-11', 'label' => false, 'class' => 'form-control', 'placeholder' => __('Sentence')));?>
                <div class="col-xs-1">
                    <button type="submit" class="btn btn-default pull-right"><i class="icon-search"></i></button>
                </div>
            </div>
        </caption>
        <thead>
            <tr>
                <th>
                    <?php echo $this->Paginator->sort('Translation.msgid', __('Sentence'), array('escape' => false, 'data-pjax' => '#page #content')); ?>
                </th>
                <th><?php echo $this->Paginator->sort('Translation.msgstr', __('Translation'), array('escape' => false, 'data-pjax' => '#page #content'));?></th>
                <th class="vertical align top"><?php echo $this->Paginator->sort('Language.language', __('Language'), array('escape' => false, 'data-pjax' => '#page #content'));?></th>
                <th class="text align center" width="80px">
                    <?php echo __('Action');?>
                    <?php echo $this->Html->link('<i class="icon-plus"></i>', array('plugin' => 'i18n', 'controller' => 'translations', 'action' => 'add'),
                            array('escape' => false, 'title' => __('Add sentence or word'), 'data-pjax' => '#page #content'));?>
                </th>
            </tr>
        </thead>
        
        <tbody>
            <?php if (sizeof($translations)):?>
                <?php for ($i = 0; $i < sizeof($translations); $i++):?>
                    <tr>
                        <td rowspan="<?php echo sizeof($translations[$i]['Translations']) + 1; ?>"><?php echo $this->Html->image('I18n.flags/' . $translations[$i]['Language']['iso'] . '.png', array('escape' => false))?> <?php echo $translations[$i]['Translation']['msgid']?></td>
                        <td><?php echo $translations[$i]['Translation']['msgstr']?></td>
                        <td><?php echo $translations[$i]['Language']['language']?></td>
                        <td class="text align center">
                            <?php echo $this->Html->link('<i class="icon-flag"></i>', array('plugin' => 'i18n', 'controller' => 'translations', 'action' => 'translate', $translations[$i]['Translation']['id']),
                                array('escape' => false, 'title' => __('Translate'), 'data-pjax' => '#page #content'));?>
                            <?php echo $this->Html->link('<i class="icon-remove"></i>', array('plugin' => 'i18n', 'controller' => 'translations', 'action' => 'delete', $translations[$i]['Translation']['id']),
                                array('escape' => false, 'title' => __('Delete'), 'data-pjax' => '#page #content'));?>
                        </td>
                    </tr>
                
                    <?php foreach ($translations[$i]['Translations'] as $language): ?>
                        <tr>
                            <td><?php echo $this->Html->image('I18n.flags/' . $language['Language']['iso'] . '.png')?> <?php echo $language['msgstr']?></td>
                            <td><?php echo $language['Language']['language']?></td>
                            <td class="text align center">
                                <?php echo $this->Html->link('<i class="icon-edit"></i>', array('plugin' => 'i18n', 'controller' => 'translations', 'action' => 'edit', $language['id']),
                                    array('escape' => false, 'title' => __('Edit'), 'data-pjax' => '#page #content'));?>
                                <?php echo $this->Html->link('<i class="icon-remove"></i>', array('plugin' => 'i18n', 'controller' => 'translations', 'action' => 'delete', $language['id']),
                                    array('escape' => false, 'title' => __('Delete'), 'data-pjax' => '#page #content'));?>
                            </td>
                        </tr>
                    <?php endforeach ?>	
                <?php endfor?>
            <?php else:?>
                <tr>
                    <td colspan="4" class="text-align-center"><?php echo __('no records found')?></td>
                </tr>
            <?php endif?>
        </tbody>
    </table>
<?php echo $this->Form->end();?>

<?php echo $this->element('paginator'); ?>
