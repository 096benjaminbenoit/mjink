window.onload = () => {
    let service = document.querySelector('#appointment_form_service');

    service.addEventListener('change', function() {
      let form = this.closest('form');
      let data = this.name + '=' + this.value;

      fetch(form.action, {
        method: form.getAttribute('method'),
        body: data,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded; charset:utf8'
        }
      }) 
      .then(response => response.text())
      .then(html => {
        let content = document.createElement('html');
        content.innerHTML = html;
        let newSelect = content.querySelector('#appointment_form_employee')
        document.querySelector('#appointment_form_employee').replaceWith(newSelect);
      })
    });
  }