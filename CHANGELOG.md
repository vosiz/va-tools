# Change log
## Current version
### 1.9.0 - HTML builder
- HtmlBuilder extends XmlBuilder — root html with auto head/body, AddToHead/AddToBody, Render (DOCTYPE)
- HtmlElement extends XmlElement — void tags by name, HTML rendering, fluent SetId/SetClass
- HtmlFactory trait — factory methods: A, Button, Div, H, Input, Li, Ol, P, Table, Tr, Ul
- elements: HtmlDivision, HtmlHeading, HtmlLink, HtmlParagraph, HtmlListItem, HtmlList, HtmlOrderedList, HtmlUnorderedList, HtmlTable, HtmlThead, HtmlTbody, HtmlTr, HtmlTd, HtmlTh
- base: Clickable extends HtmlElement — fluent SetLink
- HtmlException with element context and inner exception support; TableInvalidDimensions
- php-utils dependency bumped to >=1.11.0

## History
### 1.8.6 - XmlElement override support
- StartElement, EndElement, IsVoided private → protected
### 1.8.5 - XML Linux fix
- fix XmlElement using com_create_guid() (Windows-only) → guid() from php-utils

### 1.8.4 - QueryBuilder fixes
- fix AndWhere/OrWhere calling wrong case method name
- implement OrderBy (string or array col => ASC|DESC)
- implement Limit
### 1.8.3 - DB missing
- exception for missing DB
- catching missing DB
### 1.8.2 - Extension
- private properties of XML Element now protected
- set text option to XML Element
- set name option to XML Element

### 1.8.1 - Dependecies
- updated
- fixed

### 1.8.0 - Format - XML builder
- rendering
- attributes
- elements

### 1.7.1 - Dependecy update
- using Dictionary

### 1.7.0 - DB extension
- db connection info

### 1.6.3 - Debug fix
- DB connection ref
- credential ref

### 1.6.2 - Debug fix
- debug dumper fix

### 1.6.1 - Retvals ext
- error and tests

### 1.6.0 - Retvals
- retval
- signal

### 1.5.1 - Debugger ext
- backtracing
- formatted string dump

### 1.5.0 - Basic debugger
- debugger - basic dumper
- shorties - aliases

### 1.4.0 - DB connection and processing
- credentials
- db connection
- db querybuilder (CRUD)
- db exception

### 1.3.2 - Parser fix prefixes
- localhost fix
- no leading "slash"

### 1.3.1 - Parser fix
- parts fix
- exceptions

### 1.3.0 - Filtration
- loading crucial functions

### 1.2.0 - Autoloading
- loading crucial functions

### 1.1.0 - URL parser
- URL parser tested
- changed namespacing

### 1.0.2 - Flagword
- new Tool.Structure - flag word (flags)

### 1.0.1 - More tools
- new Tool.Parser - URL parser; basics and tests

### 1.0.0 - First release
- new Tool.Structure - node hierarchy class (node mesh); basics and tests