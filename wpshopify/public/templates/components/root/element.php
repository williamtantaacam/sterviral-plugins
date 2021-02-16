<div
   data-wpshopify-component
   data-wpshopify-component-type="<?= $data->component_type ?>"
   data-wpshopify-payload-settings="<?= $data->component_options ?>">
   
   <?php if ($data->component_type !== 'cart') { ?>
      <div class="wpshopify-loading-placeholder"><span>Loading component ...</span></div>
   <?php } else { ?>
      <style>
         .menu-item [data-wpshopify-component-type="cart"] {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
         }
      </style>
   <?php } ?>
</div>