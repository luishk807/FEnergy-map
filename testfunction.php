<?Php
  $gmap = new SplFileObject('google_map.png','w');
  $image = file_get_contents("http://maps.google.com/maps/api/staticmap?center=NY,NY&zoom=14&size=400x400&sensor=false");
  $gmap->fwrite($image);
  echo "<img src='".$gmap."'/>";
?>