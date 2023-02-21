<?php

class CustomTaxonomy {

    public $name;
    public $plural_name;
    public $genre;
    public $post_type;
    public $labels;
    public $options;

    public function __construct($singular_name, $plural_name, $genre, $post_type = null) {
        $name = $singular_name;
        $args = array();
        $args = array_merge(
            array(
                'name'    => $this->uglify_words($name),
                'plural'    => $plural_name,
                'genre' => $genre,
                'post_type' => $post_type,
                'labels'  => array(),
                'options' => array(),
                'help'    => null
            ),
        );
        $this->name = $args['name'];
        $this->plural = $args['plural'];
        $this->genre = $args['genre'];
        $this->post_type = $args['post_type'];
        $this->labels = $args['labels'];
        $this->options = $args['options'];
        if (! isset($this->labels['singular'])) {
            $this->labels['singular'] = $this->prettify_words($this->name);
        }
        if (! isset($this->labels['plural'])) {
            $this->labels['plural'] = $this->plural;
        }
        if (! isset($this->labels['genre'])) {
            $this->labels['genre'] = $this->genre;
        }
        if (!taxonomy_exists($this->name)) {
            add_action('init', array(&$this, 'register_custom_taxonomy'), 0);
        }
    }
    public function register_custom_taxonomy() {
        $labels = array(
            'name'              => $this->labels['plural'],
            'singular_name'     => $this->labels['singular'],
            'menu_name'         => $this->labels['plural'],
            'search_items'      => 'Buscar ' . $this->labels['plural'],
            'all_items'         => 'Tod' . $this->labels['genre'] . 's l' . $this->labels['genre'] . 's' . $this->labels['plural'],
            'edit_item'         => 'Editar ' . $this->labels['singular'],
            'add_new_item'      => 'Añadir nuev' . $this->labels['genre'] . ' ' . $this->labels['singular'],
        );
        $options = array_merge(
            array(
                'hierarchical'      => true,
                'labels'            => $labels,
                'rewrite'           => array('slug' => $this->get_slug($this->labels['plural'])),
                'show_admin_column' => true
            ),
            $this->options
        );
        register_taxonomy($this->name, $this->post_type, $options);
    }
    public function get_slug($name) {
        return strtolower(str_replace(' ', '-', str_replace('-', '-', $name)));
    }
    public function prettify_words($words) {
        return ucwords(str_replace('-', ' ', $words));
    }
    public function uglify_words($words) {
        return strToLower(str_replace(' ', '-', $words));
    }
}