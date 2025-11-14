document.addEventListener('DOMContentLoaded', () => {

  if(!window.MA) return;

  if(!MA.enableModal && !MA.enableBanner) return;

  const start = Date.now();
  let clicks = 0;
  document.addEventListener('click', () => clicks++);

  // Helpers
  async function postJSON(url, data = {}, includeNonce = false) {
      const headers = {
        'Content-Type': 'application/json'
      };

      if (includeNonce && MA.restNonce) {
        headers['X-WP-Nonce'] = MA.restNonce;
      }

      const res = await fetch(url, {
        method: 'POST',
        headers,
        credentials: 'same-origin',
        body: JSON.stringify(data)
      });

      return await res.json();
    }


  function shouldShowWithTTL(key, days = 7) {
      const ttl = days * 24 * 60 * 60 * 1000; // days in miliseconds
      const lastShown = localStorage.getItem(key);
      const now = Date.now();

      if (!lastShown) return true; 
      if (now - parseInt(lastShown, 10) > ttl) return true; 
      return false; 
}

  function submitModalForm(form, title, text) {

    form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = form.querySelector('input[name="email"]').value.trim();

    try {

      const data = await postJSON(MA.subscribe, { email }, true);
      
      if (data?.ok) {
          form.style.display = 'none';
          title.style.display = 'none';
          text.textContent = 'Uspješno si se prijavio/la na newsletter.'

      } else {
          alert('Greška pri prijavi. Pokušajte ponovno.');
      }

      } catch (err) {
        console.error('Newsletter error:', err);
      }
    });

  }

  // Modal
   function showModal() {
   
    if (document.querySelector('.ma-modal')) return; 

    localStorage.setItem('ma_modal_shown', Date.now());

    const modalText = MA.modalText || ''; 
    const modalHeading = MA.modalHeading || ''; 

    const html = `
     <div class="ma-modal" role="dialog" aria-modal="true">
        <div class="ma-modal__card">
          <button class="ma-modal__close" aria-label="Zatvori">&times;</button>
          <h3 class="ma-modal__title">${modalHeading}</h3>
          <p class="ma-modal__text">${modalText}</p>
          <form class="ma-modal__form">
            <input type="email" name="email" class="ma-modal__input" placeholder="tvoj@email.com" required>
            <button type="submit" class="ma-modal__button">Prijavi me</button>
          </form>
        </div>
      </div>`;

    document.body.insertAdjacentHTML('beforeend', html);

    const ajaxModal = document.querySelector('.ma-modal');
    const ajaxForm = document.querySelector('.ma-modal__form');
    const ajaxModalTitle = document.querySelector('.ma-modal__title');
    const ajaxModalText = document.querySelector('.ma-modal__text');

    ajaxModal.classList.add('ma-modal--active');
    document.body.style.overflow = 'hidden';

    submitModalForm(ajaxForm, ajaxModalTitle, ajaxModalText );


    ajaxModal.addEventListener('click', (e) => {
      if (e.target.classList.contains('ma-modal') || e.target.classList.contains('ma-modal__close')) {
        ajaxModal.remove();
        document.body.style.overflow = '';
      }
    });
  }

  // Banner
  function showBanner() {

    if (document.querySelector('.ma-banner')) return;

    localStorage.setItem('ma_banner_shown', Date.now());

    const text = MA.bannerText || ''; 
    const link = MA.bannerLink || ''; 

    const html = `
      <div class="ma-banner ma-banner--active">
        <div class="ma-banner__text">
        <a class="banner-link" href="${link}">
          <span>${text}</span> <span> → </span>    
          </a>
        </div>
        <button class="ma-banner__close" aria-label="Zatvori">&times;</button>
      </div>
      `;
    document.body.insertAdjacentHTML('beforeend', html);


    const bannerCloseButtonAjax = document.querySelector('.ma-banner__close');

    bannerCloseButtonAjax.addEventListener('click', () => {
      document.querySelector('.ma-banner').remove();
    });
  }

  // Ajax call to evaluate metrics
  async function sendEvaluateMetrics() {
    try {

        const timeOnPage = Math.floor((Date.now() - start) / 1000);

        await postJSON(MA.track, { clicks, 'time_on_page': timeOnPage });

        const data = await postJSON(MA.evaluate);

        if (!data || !data.decision) return;

        const canShowModal = shouldShowWithTTL('ma_modal_shown', MA.modalTTL || 7);
        const canShowBanner = shouldShowWithTTL('ma_banner_shown', MA.bannerTTL || 7);

        if (data.decision === 'show_discount_banner' && canShowBanner) {
          showBanner();

        } else if (data.decision === 'show_newsletter_modal' && canShowModal) {
          showModal();
        }
      
    } catch (error) {
         console.warn('FCL evaluation failed', error);
    }
  }

   sendEvaluateMetrics();

   setInterval(async () => {

    const canShowModal  = MA.enableModal  && shouldShowWithTTL('ma_modal_shown', MA.modalTTL || 7);
    const canShowBanner = MA.enableBanner && shouldShowWithTTL('ma_banner_shown', MA.bannerTTL || 7);

    if (!canShowModal && !canShowBanner) return;

    await sendEvaluateMetrics();

  }, 5000);

})