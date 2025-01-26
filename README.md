# va-tools
PHP tools to ease work

## Contains
 - structure - node hierarchy class

## Plan/roadmap
What is the plan?
- [ ] - db - database
- [ ] - parser - URL parser
- [x] - structure - node hierarchy
- [ ] - format - simple XML builder
- [ ] - format - simple HTML builder 

## Bug report
None taken

## Installation
### Composer
```
composer install vosiz/va-tools
```

## TOOLS - how to use them?
### Structure - Node hierarchy
Basically it is a node structure (tree, graph,...).

Lets setup this.

Include it to your project with desired method and use it f.e. like this:
```php
use \VaTools\Structure\NodeHierarchy as Nodeh;
```

Setup root node:
```php
$root = Nodeh::Create("ROOT");
```

Add children nodes as you need:
```php
$node_left1 = Nodeh::Create("node_L1", $root);
$node_left2 = Nodeh::Create("node_L2", $node_left1);
$node_right1 = Nodeh::Create("node_R1", $root);
```

Or set parents as you need:
```php
$node_orphan = Nodeh::Create("node_no_papa");
$node_orphan->SetParent($root);
```

Add child or children explicetely (child(ren) setup will automatically set parent to them):
```php
$node->AddChild($some_lonely_node);
$node->AddChild($array_of_lonely_nodes);
```

If needed to traverse node mesh (in-depth):
```php
    $path = []; // here are names of nodes
    $node = $root;
    do {
        $path[] = $node->GetName();
        $node = $node->Next(); // step deeper or continue, NULL is end
    }
    while($node);
```
