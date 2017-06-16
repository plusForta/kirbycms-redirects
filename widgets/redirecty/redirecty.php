<?php 

$options = [
  [
    'text' => 'View all',
    'icon' => 'pencil',
    'link' => '/panel/pages/'.c::get('redirects-list-uri', 'redirects').'/edit'
  ]
];

if(c::get('redirecty'))
  $options[] = [
    'text' => 'Link replacer',
    'icon' => 'copy',
    'link' => url(c::get('redirecty-uri', 'redirecty')),
    'target' => '_blank'
  ];

return array(
  'title' => 'Redirecty',
  'options' => $options,
  'html' => function() {

    $limit = c::get('redirecty-widget-count', 5);

    $redirects = page(c::get('redirects-list-uri', 'redirects'))->redirects()->toStructure()->flip();

    $count = $redirects->count() - $limit;
    $redirects = $redirects->limit($limit);

    $content = brick('style', file_get_contents(__DIR__ . DS . '..' . DS . '..' . DS . 'assets' . DS . 'css' . DS . 'widget.css'));

    if($redirects->count()):
      $content.= brick('p', 'Recently added redirects'.r($count > 0, ' <em>('.$count.' more...)</em>', ''));
      $content.= '<table class="structure-table"><thead><tr><th>Old</th><th>New</th></tr></thead>';
      $content.= '<tbody>';

      foreach($redirects as $redirect)
        $content.= '<tr><td><span>'.$redirect->old()->value().'</span></td><td><span>'.$redirect->new()->value().'</span></td></tr>';

      $content.= '</tbody></table>';
    endif;

    if(c::get('redirecty') && $redirects->count())
      $content.= brick('p', brick('a', 'Download CSV', ['href'=>url(c::get('redirecty-csv','redirecty-csv')), 'class'=>'btn btn-rounded']) . brick('a', 'Download JSON', ['href'=>url(c::get('redirecty-json','redirecty-json')), 'class'=>'btn btn-rounded']));

    return brick('div', $content, ['class'=>'redirecty-widget']);

  }  
);