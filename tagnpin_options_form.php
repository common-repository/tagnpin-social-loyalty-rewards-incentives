<?php
// LAYOUT FOR THE SETTINGS/OPTIONS PAGE
?>

<style>
button {
 background: #8dc63f;
   background: -moz-linear-gradient(top,  #8dc63f 0%, #8dc63f 50%, #7fb239 51%, #7fb239 100%);
   background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#8dc63f), color-stop(50%,#8dc63f), color-stop(51%,#7fb239), color-stop(100%,#7fb239));
   background: -webkit-linear-gradient(top,  #8dc63f 0%,#8dc63f 50%,#7fb239 51%,#7fb239 100%);
   background: -o-linear-gradient(top,  #8dc63f 0%,#8dc63f 50%,#7fb239 51%,#7fb239 100%);
   background: -ms-linear-gradient(top,  #8dc63f 0%,#8dc63f 50%,#7fb239 51%,#7fb239 100%);
   background: linear-gradient(top,  #8dc63f 0%,#8dc63f 50%,#7fb239 51%,#7fb239 100%);
   margin: auto;
   cursor:pointer;
   color: #fff;
   text-shadow: 1px 0px 0 rgba(0,0,0,.4);
   border-radius: 5px;
   border: none;
   font-family: cabin,sans-serif;
   display: block;
   font-weight: bold;
   padding: 5px 15px;
}
</style>

<div class="wrap">
    <?php screen_icon(); ?>
    <form action="options.php" method="post" id=<?php echo $this->plugin_id; ?>"_options_form" name=<?php echo $this->plugin_id; ?>"_options_form">
    <?php settings_fields($this->plugin_id.'_options'); ?>
    <h2>TagNpin &raquo; Options</h2>
    <table width="550" border="0" cellpadding="5" cellspacing="5">
    <tr>
        <td width="144" height="26" align="right" style="padding:0 30px 0 0;vertical-align: top;"><label style="font-weight:600" for="<?php echo $this->plugin_id; ?>[secretkey]">Secret Key:</label> </td>
        <td id="key-holder" width="366" style="padding:5px;"><input placeholder="Got a tagNpin Secret key? Enter it here." id="tagnpin_key" name="<?php echo $this->plugin_id; ?>[secretkey]" type="text" value="<?php echo $options['secretkey']; ?>" size="40" /></td>
    </tr>
    <tr>
        <td width="144" height="16" align="right"></td>
        <td width="366" style="border-bottom: 1px solid #CCC;padding:0 0 10px 0;"><p style="margin-top:3px;font-size:10px;">You can find Secret key in tagnpin admin panel -> "Install Plugins" -> "Brand Authentication" -> "Secret Key". You may Click <a href="http://www.tagnpin.com/web/tagnpin2/brandLogin">here</a> to sign up on tagNpin.</p></td>
    </tr>    
    
    <tr>
        <td width="144" height="26" align="right" style="padding:0 30px 0 0;vertical-align: top;"><label style="font-weight:600" for="<?php echo $this->plugin_id; ?>[brandID]">brandID:</label> </td>
        <td id="key-holder" width="366" style="padding:5px;"><input placeholder="Got a TagNpin brandid? Enter it here." id="tagnpin_brandid" name="<?php echo $this->plugin_id; ?>[brandID]" type="text" value="<?php echo $options['brandID']; ?>" size="40" /></td>
    </tr>
    <tr>
        <td width="144" height="16" align="right"></td>
        <td width="366" style="border-bottom: 1px solid #CCC;padding:0 0 10px 0;"><p style="margin-top:3px;font-size:10px;">You can find brandid in tagnpin admin panel -> "Install Plugins" -> "Brand Authentication" -> "BrandID"</p></td>
    </tr>    
        
    <!-- 
    <tr>
        <td width="144" height="26" align="right" style="margin-top:20px;padding:0 30px 0 0;vertical-align: top;"><label style="font-weight:600" for="<?php //echo $this->plugin_id; ?>[name]">Name:</label> </td>
        <td width="366" style="border-bottom: 1px solid #CCC;padding:0 0 10px 0;"><input name="<?php //echo $this->plugin_id; ?>[name]" type="text" value="<?php //if (isset($options['name'])) echo $options['name']; ?>" size="40" /></td>
    </tr>
     -->
          
    <tr>
        <td width="144" height="26" align="right" style="margin-top:20px;padding:0 30px 0 0;vertical-align: top;"><label style="font-weight:600" for="<?php echo $this->plugin_id; ?>[enable_rewards]">Enable rewards:</label> </td>
        <td width="366"><input type="hidden" name="<?php echo $this->plugin_id; ?>[enable_rewards]" value="0" /><input name="<?php echo $this->plugin_id; ?>[enable_rewards]" type="checkbox" <?php echo ($options['enable_rewards'] || !isset($options['enable_rewards']))?'checked="checked"':''; ?> /></td>
    </tr>
    
    <!-- 
    <tr id="<?php //echo $this->plugin_id; ?>[xpos]">
        <td width="144" height="26" align="right"><label for="<?php //echo $this->plugin_id; ?>[xpos]">Rewards Floating tab Position</label> </td>
        <td width="366"><select name="<?php //echo $this->plugin_id; ?>[xpos]">
            <option value="left" <?php //if ($options['xpos'] == 'left') echo 'selected="selected"'; ?>>Left</option>
            <option value="right" <?php //if ($options['xpos'] == 'right') echo 'selected="selected"'; ?>>Right</option>
        </select></td>
    </tr>
    <tr id="<?php //echo $this->plugin_id; ?>[ypos]">
        <td width="144" height="26" align="right"><label for="<?php //echo $this->plugin_id; ?>[ypos]"></label> </td>
        <td width="366" style="border-bottom: 1px solid #CCC;padding:0 0 10px 0;">
        <select name="<?php //echo $this->plugin_id; ?>[ypos]">
            <option value="top" <?php //if ($options['ypos'] == 'top') echo 'selected="selected"'; ?>>Top</option>
            <option value="bottom" <?php //if ($options['ypos'] == 'bottom' || empty($options['ypos'])) echo 'selected="selected"'; ?>>Bottom</option>
        </select>
    </tr>
    -->
    
	<!-- Points on comments -->
    <tr>
        <td width="144" height="26" align="right" style="padding:0 30px 0 0;vertical-align: top;"><label style="font-weight:600" for="<?php echo $this->plugin_id; ?>[commentPoints]">Points on Comment:</label> </td>
        <td id="key-holder" width="366" style="padding:5px;"><input placeholder="Points to be given on comments." id="tagnpin_comment_points" name="<?php echo $this->plugin_id; ?>[commentPoints]" type="text" value="<?php echo $options['commentPoints']; ?>" size="40" /></td>
    </tr>
    <tr>
        <td width="144" height="16" align="right"></td>
        <td width="366" style="border-bottom: 1px solid #CCC;padding:0 0 10px 0;"><p style="margin-top:3px;font-size:10px;">Please enter number of points you want to give on comments. Enter 0 if no points are to be given</p></td>
    </tr>
	<!-- Points on coments close -->

    <tr>
        <td width="144" height="26" align="right"> </td>
        <td width="366"><input type="submit" name="submit" value="Save Options" class="button-primary" /><div>By installing TagNpin you agree to the <a href="http://www.tagnpin.com/web/tagnpin2/policy">Customer Agreement</a></div></td>
    </tr>
    </table>
    </form>
</div>
