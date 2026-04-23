/**
 *
 * @param url
 * @param data
 * @param request
 * @returns {Promise<any|null>}
 * uses : <b>const data = await ajaxRequest('/api/user', { name: 'Manu' }, 'POST');</b>
 */
async function ajaxRequest(url, data = null, request = 'POST') {
    try {
        const options = {
            method: request.toUpperCase(),
            headers: {
                'Content-Type': 'application/json'
            }
        };

        if (data) {
            options.body = JSON.stringify(data);
        }

        const response = await fetch(url, options);

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Request failed:', error);
        return null;
    }
}