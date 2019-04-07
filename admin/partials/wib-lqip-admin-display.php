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
        WALK in BCN
    </h1>
    <h2>
        LQIP - Low quality image placeholder
    </h2>
    <form action="options.php" method="post">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        LQIP quality (1 ~ 100)
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span>
                                    LQIP quality (1 ~ 100)
                                </span>
                            </legend>
                            <label for="wib_lqip_enabled">
                                <input name="wib_lqip_quality"
                                       id="wib_lqip_quality"
                                       type="number"
                                       value="<?php echo esc_attr(get_option('wib_lqip_quality')); ?>" />
                            </label>
                        </fieldset>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr />
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        Thumbnail (Featured image)
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span>
                                    Thumbnail (Featured image)
                                </span>
                            </legend>
                            <label for="wib_lqip_thumbnail_enabled">
                                <input name="wib_lqip_thumbnail_enabled"
                                       id="wib_lqip_thumbnail_enabled"
                                       type="checkbox"
                                       value="<?php echo esc_attr(get_option('wib_lqip_thumbnail_enabled')); ?>" />
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        Media library (Attachments)
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span>
                                    Media library (Attachments)
                                </span>
                            </legend>
                            <label for="wib_lqip_attachment_enabled">
                                <input name="wib_lqip_attachment_enabled"
                                       id="wib_lqip_attachment_enabled"
                                       type="checkbox"
                                       value="<?php echo esc_attr(get_option('wib_lqip_attachment_enabled')); ?>" />
                            </label>
                        </fieldset>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr />
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        WooCommerce - Product (Archive)
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span>
                                    WooCommerce - Product (Archive)
                                </span>
                            </legend>
                            <label for="wib_lqip_woocommerce_archive_product_enabled">
                                <input name="wib_lqip_woocommerce_archive_product_enabled"
                                       id="wib_lqip_woocommerce_archive_product_enabled"
                                       type="checkbox"
                                       value="<?php echo esc_attr(get_option('wib_lqip_woocommerce_archive_product_enabled')); ?>" />
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        WooCommerce - Product (Single)
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span>
                                    WooCommerce - Product (Single)
                                </span>
                            </legend>
                            <label for="wib_lqip_woocommerce_single_product_enabled">
                                <input name="wib_lqip_woocommerce_single_product_enabled"
                                       id="wib_lqip_woocommerce_single_product_enabled"
                                       type="checkbox"
                                       value="<?php echo esc_attr(get_option('wib_lqip_woocommerce_single_product_enabled')); ?>" />
                            </label>
                        </fieldset>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary"
                   value="<?php _e('Save Changes');?>" />
        </p>
        <?php settings_fields('wib-lqip-settings-field');?>
    </form>
</div>