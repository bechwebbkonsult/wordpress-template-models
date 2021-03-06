# Template models in Wordpress

Template models are class methods that is executed immediately before WordPress includes the predetermined template file. If you have data that you want to be bound to a template each time that template is rendered, a template model can help you organize that logic into a single location.

## Usage
A template model is a class where you can put some complex logic for your templates. You can create a template model by extending the provided `Bechwebb\TemplateModels\TemplateModel`.

```php
class HomeTemplateModel extends TemplateModel
{
    public $terms = [];

    public function __construct()
    {
        foreach (get_terms(['taxonomy' => 'bf_calendar', 'hide_empty' => false]) as $term) {
            $term->url = get_term_link($term->term_id);
            $this->terms[] = $term;
        }
    }
}
```

All template models need to be registered to the desired wordpres template.
```php
use Bechwebb\TemplateModels\TemplateModelProvider;

$templateModelProvider = new TemplateModelProvider;
$templateModelProvider->register('/home.php', \App\TemplateModels\HomeTemplateModel::class);
```

In the template all public properties from the template model is now available.
```php
<?php foreach ($terms as $term) : ?>
    <div class="row <?= $term->active ?>">
        <div class="col">
            <a href="<?= $term->url ?>"><?= $term->name ?></a>
        </div>
    </div>
<?php endforeach; ?>
```

### Manually using template models
You can also manually load a template model from any template and use arguments

```php
use Bechwebb\TemplateModels\TemplateModel;

class CalendarSidebarTemplateModel extends TemplateModel
{
    public $title = '';
    public $posts = [];

    public function __construct($title, $post_type)
    {
        $this->title = $title;

        foreach (get_posts((['post_type' => '$post_type']) as $post) {
            $post->url = get_post_permalink($post->ID);

            $this->posts[] = post;
        }
    }
}
```

Register the template model
```php
$templateModelProvider->register('/theme-templates/calendar-sidebar.php', \App\TemplateModels\CalendarSidebarTemplateModel::class);
```

Use `get_model_template()` to include the template
```php
get_model_template('/theme-templates/calendar-sidebar.php', 'Sidebar title', 'bf_calendar');
```
