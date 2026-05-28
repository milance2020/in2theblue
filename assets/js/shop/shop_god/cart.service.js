class CartService {

    static async request(action, payload = {}) {

        const apiCart = window.APP_URLS?.apiCart ?? 'api/cart.php';
        const res = await fetch(`${apiCart}?action=${encodeURIComponent(action)}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        });
        

        if (!res.ok) {
            throw new Error(`Cart API error (${res.status})`);
        }

        const data = await res.json();

        if (data === null || typeof data !== 'object') {
            throw new Error('Invalid cart API response');
        }

        return data;
    }

    static add(id, size, qty = 1) {
        return this.request('add', { id, size, qty });
    }

    static remove(id, size) {
        return this.request('remove', { id, size });
    }

    static increase(id, size) {
        return this.request('increase', { id, size });
    }

    static decrease(id, size) {
        return this.request('decrease', { id, size });
    }

    static get() {
        return this.request('get');
    }
}