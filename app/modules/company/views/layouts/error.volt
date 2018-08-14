<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
    <meta charset="utf-8">
    <style type="text/css">
        *{
            font-family:        Consolas, Courier New, Courier, monospace;
            font-size:            14px;
        }
        body {
            background-color:    #fff;
            margin:                40px;
            color:                #000;
        }
        #content  {
            border:                #999 1px solid;
            background-color:    #fff;
            padding:            20px 20px 12px 20px;
            line-height:160%;
        }

        h1 {
            font-weight:        normal;
            font-size:            14px;
            color:                #990000;
            margin:             0 0 4px 0;
        }
    </style>
</head>
<body>

<div id="content">
    {% if  title %}
        <h1>{{ title }}</h1>
    {% endif %}
    {% if debug %}
    <pre>{{ code }} - {{ message }}</pre>
    <pre>{{ trace }}</pre>
    {% endif %}
</div>
</body>
</html>
