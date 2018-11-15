<?php
class starthookController extends Kirby\Component\Template {
  public function data($page, $data = []) {

    if($page instanceof Page) {
      $data = array_merge(
        $page->templateData(), 
        $data,
        $page->controller($data),
        $this->starthook($page)
      );
    }

    // apply the basic template vars
    return array_merge(array(
      'kirby' => $this->kirby,
      'site'  => $this->kirby->site(),
      'pages' => $this->kirby->site()->children(),
      'page'  => $page
    ), $data);
  }

  public function starthook($page) {
    $starthook = c::get('starthook');
    if(is_callable($starthook)) {
      $callback = call($starthook, $page);
    }
    if($callback && is_array($callback)) return $callback;
    return [];
  }
}

$kirby->set('component', 'template', 'starthookController');
