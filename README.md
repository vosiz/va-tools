# va-tools
PHP tools to ease work

## Important notes
Used namespace: Vosiz\VaTools\particular-part

## Contains
### Parser
- URL parser
### Structure
- node hierarchy class
- flag system classes
### Filter
- data filtration classes

## Plan/roadmap
What is the plan?
- [ ] - db - database
- [x] - parser - URL parser
- [x] - structure - node hierarchy
- [x] - structure - flags (flag system)
- [ ] - format - simple XML builder
- [ ] - format - simple HTML builder 
- [x] - filter - basic filter
- [x] - filter - string filtration

## Bug report
None taken

## Installation
### Composer
Install with
```
composer install vosiz/va-tools
```

Update with (dependencies/required)
```
composer update
```

## TOOLS - how to use them?
### Parser - URL parser
Parses URL to user defined structure

Include classes, something like this:
```php
use Vosiz\VaTools\Url\UrlStructure as UrlStruct;
use Vosiz\VaTools\Url\UrlParser as Parser;
```

Then you need to define structure model you want to use, like this:
```php
// asuming structure www.myurl.com/<controller>/<action>?par=value... or something similar
// somethng like: wwww.myurl.com/user/profile/$userid=123...
$struct = UrlStruct::Create('www.myurl.com', ['controller', 'action']);
```

Pass it to a parser
```php
$parser = new Parser($struct);
```

Access parts you need
```php
$parser->GetPartByKey('action');
```

### Structure - Node hierarchy
Basically it is a node structure (tree, graph,...).

Lets setup this.

Include it to your project with desired method and use it f.e. like this:
```php
use Vosiz\VaTools\Structure\NodeHierarchy as Nodeh;
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

### Structure - Flag system
It is a flag system suitable for configuration f.e. Instead of keeping distinct variables in class, you can you instance of this to keep it.
It is more databse friendly as it works with integers, but for logic returns booleans.

Lets see how it works.

Include flagword to your project:
```php
use Vosiz\VaTools\Structure\Flagword as Fword;
```

Or just the flag if you want to use it more traditionaly (enchanced bools basically):
```php
use Vosiz\VaTools\Structure\Flag as Flag
```
> [!NOTE]
> Flagword works with Flag

Flag has a simple interface, it works basically like boolean does, but it resolves "int vs bool" potencial issue, so you do not need to wory about having different logical values in configuration value.

```php
class MyClassWithSimpleConfig {

    private $ConfigFlag;    // good old way
    private $NewConfigFlag; // Flag system

    public function OldConfig($value) {

        // check if int or bool
        ...

        $this->ConfigFlag = $processed_value;
    }

    public function NewConfig($value) {

        $this->NewConfigFlag = new Flag(); // flag is unset ( 0 / false)

    	// in case you want to set flag 
        $this->NewConfigFlag = new Flag(true);  // = 1

        // -- OR -- 
        $this->NewConfigFlag = new Flag();  // = 0
        $this->NewConfigFlag->Set();        // = 1
    }
}
```

Assuming you have getter for $NewConfigFlag field - you can manipulate with flag as you want.

```php
$flag = $myclass->GetConfig(); // returns $NewConfigFlag (example)

// read flag value
$flag->IsSet(); // always bool

// set new value
$flag->Set($value); // default is 1

// unset flag
$flag->Unset(); // always 0

// toggle (switch)
$flag->Toggle(); // switching values

```

If you want to work with your variable as flag, simply convert it.

```php
$b = false;
$i = 1;

$flag1 = Flag::ToFlag($b);
$flag2 = Flag::ToFlag($i);

```

Flagword is basically structure with Flags in it. Lets create one or two.
```php
class MyClassWithMoreConfig {

    private $ConfigurationGeneral;
    private $ConfigurationSpecifics;
    ...

    public function __construct() {

        $this->ConfigurationGeneral(2);     // I know i do not need more than 16 flags (2 bytes)
        $this->ConfigurationSpecifics(5);   // I know i do not need more than 40 flags (5 bytes)
        ...
    }
}
```

> [!NOTE]
> Flag system is designed to be lightweight so it will only take as much space is needed - detecting your architecture (32/64) and setup structure to be minimalistic.

Lets asume we have getters to configs.

```php
$general = $myclass->GetConfig('general'); // or somthing like that
$specs = $myclass->GetConfig('specifics'); // or somthing like that
```

Now there are 2 ways to work with Flagword.
1. As capsule for Flags
2. Smart-capsule for Flags

In first case, the work is very similar to work with Flag(s).
Lets setup some general configuration.

```php
$general->SetFlag(4);           // config of index 4 is set (default)
$general->SetFlag(5, 1);        // config of index 5 is set
$general->SetFlag(8, 0);        // config of index 8 is unset
$general->SetFlag(9, $conf9);   // config of index 9 is set whatever value is stored in $conf9
...
// all others are set to 0 automatically
```

Now you can read them.
```php
$flag8 = $general->ByIndex(8); // returns Flag of index 8
$gcfg8 = $flag8->Isset();
```

Second way is to use built-in functions. Lets use specifics and lets asume we have to define certain specifics to check later.
I our little scenario lets asume we have defined constants of indeces.

```php
$system = 0; // 0x00 - system flags in first 2 bytes
$admin = 16; // 0x10 - admin flags after second byte
...

$system_ready   = $system + 0;      // 0x00
$system_online  = $system + 5;      // 0x05
...

$admin_logged   = $admin + 1;       // 0x11
...

// want to name and register our mandatory flags
$specs->RegisterFlag($system_ready, 'System-ready');
$specs->RegisterFlag($admin_logged, 'Admin-loggd'); // typo
$specs->RegisterFlag($system_online, 'System-online');
...

// we later in system found typo (or whatever reason to rename it)
$specs->RegisterFlag($admin_logged, 'Admin-logged');
```

Now we can check if every mandatory flag was set in system if we want to. For example all mandatory checks were done, ready to fly boss.
```php
if($specs->IsAllSet()) {

    // all set, move on...
    ...
}
```

### Data filtration - string
Simple filtration class which can be used to filter collection of data - in this case strings.

Lets assume we want to know if "MyNamespace" is correctly included (visible in list of defined classes list - something like "MyNamespace\MyClass").

Start with creating instance:

```php
$data = classlist(); // in this example gets a class list - defined in [Link package](https://packagist.org/packages/vosiz/php-utils)
$filter = new StringFilter($data);
```

This step is optional and depends on situation, but class list will surely contains something like "MyNamspace\...\MyClass" or in this case lets assume "MyNamspace\MyClass" in short.

If we want to use full-string name, thats ok. Maybe we are having multipleclasses (like MyClass2 etc...). 
In this case we want to split each entry of list to reference full-string.

This is done simply by calling Split method.

```php
$filter->Split("\\"); // in this case escaped "\"
```

Now we have 2 references to original data.
"MyNamespace"   => "MyNamspace\MyClass"
"MyClass"       => "MyNamspace\MyClass"

Now simply create a query with params and call filtration. Query is composed of command (string name of method or alias) and (if needed) parameters.

In our scenario we need to filter "our" classes, so lets filter it by "MyNamespace" to get all items from original list, which contains "MyNamespace" string.

```php
// we used Split before
$filter->Filter(['contains' => 'MyNamespace']);

// we did not used Split, we want precise item
$filter->Filter(['contains' => 'MyNamspace\\MyClass']);
```

> [!NOTE]
> Filtration is made in cascades. So you can filter filtered data simply by calling another Filter().

> [!TIP]
> You can reset filter anytime (to work on same dataset) with calling ResetFilter().

Now we can receive filtered dataset.

```php
// gets filtered data
$fdata = $filter->GetFiltered();

// just print it (before filtration)
$filter->PrintAll();

// just print it (after filtration)
$filter->PrintFiltered();
```

