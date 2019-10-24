<?php
$fsfp_image_keywords  = "Old_Car_City,    White, Georgia, Black_and_White, Kodak_Tri-X               ,Hasselblad_500CM";
$fsfp_keywords_array = preg_split("/(\s+,\s+|\s+|,)/", $fsfp_image_keywords);

print_r($fsfp_keywords_array);
?>
