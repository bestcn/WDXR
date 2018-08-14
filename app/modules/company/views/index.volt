<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $this->tag->getTitle(); ?>

    {{ stylesheet_link("/company_css/bootstrap.min.css") }}
    {{ stylesheet_link("/font-awesome/css/font-awesome.css") }}
    {{ stylesheet_link("/company_css/animate.css") }}
    {{ stylesheet_link("/company_css/style.css") }}
    {{ stylesheet_link("/company_css/css2.css") }}
    {{ stylesheet_link("/company_css/dropzone/basic.css") }}
    {{ stylesheet_link("/company_css/dropzone/dropzone.css") }}


</head>
{{ content() }}
</html>
