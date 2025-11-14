document.addEventListener('DOMContentLoaded', () => {
  
  const select   = document.getElementById('ma_fcl_examples');
  const textarea = document.querySelector('[name="ma_fcl_code"]');

  const examples = {
    modal_10s: `// Ako je korisnik na stranici manje od 10 sekundi → pokaži modal
if ($time_on_page < 10) {
  $return = 'show_newsletter_modal';
} else {
  $return = 'none';
}`,
    banner_5clicks: `// Ako je korisnik kliknuo više od 5 puta → pokaži banner
if ($clicks > 5) {
  $return = 'show_discount_banner';
} else {
  $return = 'none';
}`,
    banner_3visits: `// Ako je korisnik posjetio više od 3 puta → pokaži banner
if ($visits > 3) {
  $return = 'show_discount_banner';
} else {
  $return = 'none';
}`,
    modal_mobile: `// Ako korisnik koristi mobitel → pokaži modal
if ($is_mobile == true) {
  $return = 'show_newsletter_modal';
} else {
  $return = 'none';
}`,
    banner_croatia: `// Ako je korisnik iz Hrvatske (country = 'HR') → pokaži banner
if ($country == "HR") {
  $return = 'show_discount_banner';
} else {
  $return = 'none';
}`,
  };

  if (select && textarea) {
    select.addEventListener('change', () => {
      const code = examples[select.value];
      if (code) {
        textarea.value = code;
      }
    });
  }
});
