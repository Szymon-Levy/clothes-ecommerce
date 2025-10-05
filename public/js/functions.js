/**
 * Limits function execution to given time interval
 */
const throttleFunction = (callback, interval = 100) => {
    let isRunning = false

    return (...args) => {
        if (!isRunning) {
            isRunning = true

            callback.apply(this, args)

            setTimeout(() => {
                isRunning = false
            }, interval);
        }
    }
}

/**
 * Returns cookie value from given name
 */
const getCookie = (name) => {
    let cookieValue = null;

    if (document.cookie && document.cookie !== '') {
        const cookies = document.cookie.split(';');

        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].trim();

            if (cookie.substring(0, name.length + 1) === (name + '=')) {
                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                break;
            }
        }
    }
    
    return cookieValue;
}