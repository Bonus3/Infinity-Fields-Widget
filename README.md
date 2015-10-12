# Infinity Fields Widget
Widget to add infinity custom fields.
Inspired in the ACF plugin, however, focused to be used in Widgets

# How to use
After installation, will enable use of Widget **Infinity-Fields-Widget** in **Appearance** > **Widget** in WordPress Panel

![](http://i.imgur.com/ddVWWUo.jpg)

Add a title and for each dynamic field, insert a the **label** and a the **value**.
To add new fields, click in button **Add**

![](http://i.imgur.com/UTU7pvS.jpg)

# Result
The widget result in a list.

![](http://i.imgur.com/OjJ13Vm.jpg)

# Developers
You can get the values in an array, using the function **ifw_get_fields()** that return an array two-dimensional.

	$ifws = ifw_get_fields();
-----------------------------------------------


**So you can use in the loop**
-----------------------------------------------
<p>foreach ($ifws as $ifw)</p>
echo '\\<p\\>' . $ifw['label'] . ' -> ' . $ifw['value'] . '\\</p\\>';