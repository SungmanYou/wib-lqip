<?php
    /**
     * @link       https://github.com/SungmanYou
     * @since      1.0.0
     * @package    Wib_Lqip
     * @subpackage Wib_Lqip/admin/partials
     */
?>
<div class="wrap">
    <h1>
        WALK in BCN - LQIP (Low Quality Image Placeholder)
    </h1>
    <hr />
    <form method="POST"
          action="options.php">
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="wib_lqip_quality">
                        <?php echo __('Quality', 'wib-lqip'); ?>
                    </label>
                </th>
                <td>
                    <input name="wib_lqip_quality"
                           type="number"
                           id="wib_lqip_quality"
                           value="<?php echo esc_attr(get_option('wib_lqip_quality')); ?>"
                           min="1"
                           max="99"
                           class="regular-text"
                           aria-describedby="wib_lqip_quality-description" />
                    <p class="description"
                       id="wib_lqip_quality-description">
                        1 ~ 99
                    </p>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit"
                   class="button-primary"
                   value="<?php _e('Save Changes');?>" />
        </p>
        <?php settings_fields('wib-lqip-settings-group');?>
    </form>
</div>