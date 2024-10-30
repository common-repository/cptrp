<div class="wrap">
    <h1><?php _e('CPRTP - Impostazioni', 'cptrp')?></h1>

	<em><?php _e('Inserisci lo shortcode [cptrp] nel punto in cui si desidera visualizzare i post correlati', 'cptrp')?></em>

    <form id="cptrp-settings" method="post" action="options.php"> 
        <?php @settings_fields('cptrp-group'); ?>
        <?php @do_settings_fields('cptrp-group'); ?>

        <?php do_settings_sections('cptrp'); ?>

        <?php @submit_button(); ?>
    </form>
</div>