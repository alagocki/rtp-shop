import Plugin from 'src/plugin-system/plugin.class';

export default class CustomizableCart extends Plugin {
    init() {
        this.el.addEventListener('change', (event) => {
            const lineItemId = event.target.getAttribute('id').replace('customizable-', '');
            const isChecked = event.target.checked;

            // AJAX-Request an Shopware schicken
            this._updateLineItem(lineItemId, isChecked);
        });
    }

    _updateLineItem(lineItemId, isChecked) {

        fetch('/cart/update-customizable', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                lineItemId: lineItemId,
                customizable: isChecked
            })
        }).then(response => response.json())
            .then(data => console.log('Update erfolgreich:', data))
            .catch(error => console.error('Fehler:', error));
    }
}