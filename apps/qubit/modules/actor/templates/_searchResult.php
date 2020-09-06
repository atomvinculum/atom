<?php use_helper('Text') ?>

<?php if (!empty($doc['hasDigitalObject'])): ?>
  <article class="search-result has-preview">
<?php else: ?>
  <article class="search-result">
<?php endif; ?>

  <?php if (!empty($doc['hasDigitalObject'])): ?>
    <div class="search-result-preview">
      <a href="<?php echo url_for(array('module' => 'actor', 'slug' => $doc['slug'])) ?>">
        <div class="preview-container">
          <?php if (isset($doc['digitalObject']['thumbnailPath'])): ?>
            <?php echo image_tag($doc['digitalObject']['thumbnailPath'],
              array('alt' => isset($doc['digitalObject']['digitalObjectAltText']) ? $doc['digitalObject']['digitalObjectAltText'] : truncate_text(strip_markdown(get_search_i18n($doc, 'authorizedFormOfName', array('allowEmpty' => false, 'culture' => $culture))), 100))) ?>
          <?php else: ?>
            <?php echo image_tag(QubitDigitalObject::getGenericIconPathByMediaTypeId($doc['digitalObject']['mediaTypeId']),
              array('alt' => isset($doc['digitalObject']['digitalObjectAltText']) ? $doc['digitalObject']['digitalObjectAltText'] : truncate_text(strip_markdown(get_search_i18n($doc, 'authorizedFormOfName', array('allowEmpty' => false, 'culture' => $culture))), 100))) ?>
          <?php endif; ?>
        </div>
      </a>
    </div>
  <?php endif; ?>

  <div class="search-result-description">
  
    <p class="title"><?php echo link_to(render_value_inline(get_search_i18n($doc, 'authorizedFormOfName', array('allowEmpty' => false, 'culture' => $culture))), array('module' => 'actor', 'slug' => $doc['slug'])) ?></p>

    <?php echo get_component('object', 'clipboardButton', array('slug' => $doc['slug'], 'wide' => false)) ?>

    <ul class="result-details">

      <?php if (!empty($doc['descriptionIdentifier'])): ?>
        <li class="reference-code"><?php echo render_value_inline($doc['descriptionIdentifier']) ?></li>
      <?php endif; ?>

      <?php if (!empty($doc['entityTypeId']) && null !== $term = QubitTerm::getById($doc['entityTypeId'])): ?>
        <li><?php echo render_value_inline($term) ?></li>
      <?php endif; ?>

      <?php if (strlen($dates = get_search_i18n($doc, 'datesOfExistence', array('culture' => $culture))) > 0): ?>
        <li><?php echo render_value_inline($dates) ?></li>
      <?php endif; ?>
      <li>
        <?php
        // print_r($doc);
        //print_r($aggs['repository']);
        // print_r($aggs);

        $criteria = new Criteria();
        $criteria->addJoin(QubitActor::ID, QubitActorI18n::ID);
        $criteria->add(QubitActorI18n::AUTHORIZED_FORM_OF_NAME, $doc['i18n']['en']['authorizedFormOfName']);

        $criteria->addJoin(QubitActor::ID, QubitRelation::OBJECT_ID);
        $criteria->add(QubitRelation::TYPE_ID, QubitTerm::MAINTAINING_REPOSITORY_RELATION_ID);
        $criteria->add(QubitRelation::SUBJECT_ID, $doc['maintainingRepositoryId']);

        $r = QubitActor::getOne($criteria, []);
        if ($r !== null) {
          $repository = $r->getMaintainingRepository();
          echo render_title($repository);
        }


/*          $present = false;

          foreach ($aggs['repository'] as $agg) {
            if($agg['key'] == $doc['maintainingRepositoryId']) {
              $present = true;
              echo $agg['display'];
            }
          } */

/*          if (!$present) {
            echo $doc['i18n']['en']['authorizedFormOfName'];
          }*/
        ?>
      </li>


    </ul>

    <?php if (null !== $history = get_search_i18n($doc, 'history', array('culture' => $culture))): ?>
      <div class="history"><?php echo render_value($history) ?></div>
    <?php endif; ?>
  </div>

</article>
