document.addEventListener('DOMContentLoaded', () => {

  if(!window.MA || !MA.rest) return;

  let clicks = 0;
  const start = Date.now();
  const bannerCloseButton = document.querySelector('.ma-banner__close');

  if (bannerCloseButton) 
    {
      bannerCloseButton.addEventListener('click', () => {
      document.querySelector('.ma-banner').remove();
    });
    }

  document.addEventListener('click', () => clicks++);

   async function postJSON(url, data = {}) {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'same-origin',
      body: JSON.stringify(data)
    });
    return await res.json();
  }


   function showModal() {
    if (document.querySelector('.ma-modal')) return; 
    const html = `
     <div class="ma-modal" role="dialog" aria-modal="true">
        <div class="ma-modal__card">
          <button class="ma-modal__close" aria-label="Zatvori">&times;</button>
          <h3 class="ma-modal__title">Pridruži se newsletteru</h3>
          <p class="ma-modal__text">Dobij novosti i ponude — prijavi se ispod.</p>
          <form class="ma-modal__form">
            <input type="email" class="ma-modal__input" placeholder="tvoj@email.com" required>
            <button type="submit" class="ma-modal__button">Prijavi me</button>
          </form>
        </div>
      </div>`;

    document.body.insertAdjacentHTML('beforeend', html);

    const modal = document.querySelector('.ma-modal');

    modal.classList.add('ma-modal--active');
    document.body.style.overflow = 'hidden';

    modal.addEventListener('click', (e) => {
      if (e.target.classList.contains('ma-modal') || e.target.classList.contains('ma-modal__close')) {
        modal.remove();
        document.body.style.overflow = '';
      }
    });
  }

  function showBanner() {
    if (document.querySelector('.ma-banner')) return;

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

  async function sendEvaluateMetrics() {
    try {

        const timeOnPage = Math.floor((Date.now() - start) / 1000);

        await postJSON(MA.rest, { clicks, 'time_on_page': timeOnPage });

        const data = await postJSON(MA.evaluate);

        if (!data || !data.decision) return;

        if (data.decision === 'show_discount_banner') {
          showBanner();
          localStorage.setItem('ma_banner_shown', '1');

        } else if (data.decision === 'show_newsletter_modal') {
          showModal();
          localStorage.setItem('ma_modal_shown', '1');
        }

      
    } catch (error) {
         console.warn('FCL evaluation failed', error);
    }
  }

  // const bannerAlreadyShown = localStorage.getItem('ma_banner_shown');
  // const modalAlreadyShown = localStorage.getItem('ma_modal_shown');

    const bannerAlreadyShown = null;
    const modalAlreadyShown = null;

    async function handleMetricsCycle() {
      if (bannerAlreadyShown || modalAlreadyShown) return;
    
      await sendEvaluateMetrics();
  }

  setTimeout(handleMetricsCycle, 30000);

  window.addEventListener('beforeunload', handleMetricsCycle);

})