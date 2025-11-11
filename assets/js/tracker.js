document.addEventListener('DOMContentLoaded', () => {

  if(!window.MA || !MA.rest) return;

  let clicks = 0;
  const start = Date.now();

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
      <div class="ma-modal" style="position:fixed;inset:0;background:rgba(0,0,0,.6);display:flex;align-items:center;justify-content:center;z-index:9999">
        <div style="background:#fff;padding:24px;border-radius:12px;max-width:480px;width:90%">
          <button class="ma-close" style="float:right;font-size:20px">&times;</button>
          <h3>Pridruži se newsletteru</h3>
          <p>Dobij novosti i ponude — prijavi se ispod.</p>
          <form>
            <input type="email" placeholder="tvoj@email.com" required style="width:100%;padding:10px;margin:8px 0;border:1px solid #ddd;border-radius:8px;">
            <button type="submit" style="padding:10px 16px;border-radius:8px;background:#111;color:#fff;border:none">Prijavi me</button>
          </form>
        </div>
      </div>`;
    document.body.insertAdjacentHTML('beforeend', html);
    document.querySelector('.ma-close').addEventListener('click', () => {
      document.querySelector('.ma-modal').remove();
    });
  }

  function showBanner() {
    if (document.querySelector('.ma-banner')) return;
    const html = `
      <div class="ma-banner" style="position:fixed;bottom:16px;left:16px;right:16px;background:#111;color:#fff;padding:12px 16px;border-radius:10px;z-index:9998">
        <strong>Specijalna ponuda:</strong> Ostvari 10% popusta danas! <a href="/shop" style="color:#fff;text-decoration:underline">Kupi sada →</a>
      </div>`;
    document.body.insertAdjacentHTML('beforeend', html);
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
         console.warn('FCL evaluation failed', err);
    }
  }

  const bannerAlreadyShown = localStorage.getItem('ma_banner_shown');
  const modalAlreadyShown = localStorage.getItem('ma_modal_shown');

    async function handleMetricsCycle() {
      if (bannerAlreadyShown || modalAlreadyShown) return; 
      await sendEvaluateMetrics();
  }

  setTimeout(handleMetricsCycle, 1000);

  window.addEventListener('beforeunload', handleMetricsCycle);

})