const api = {
    get: (url, headers = {}) => {
        return fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                ...headers
            }
        }).then(handleResponse);
    },
    
    post: (url, data, isFormData = false, headers = {}) => {
        const options = {
            method: 'POST',
            headers: isFormData ? {} : { 'Content-Type': 'application/json', ...headers },
            body: isFormData ? data : JSON.stringify(data)
        };
        
        return fetch(url, options).then(handleResponse);
    },
    
    put: (url, data, headers = {}) => {
        return fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...headers
            },
            body: JSON.stringify(data)
        }).then(handleResponse);
    },
    
    delete: (url, headers = {}) => {
        return fetch(url, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                ...headers
            }
        }).then(handleResponse);
    }
};

// Response handler
function handleResponse(response) {
    return response.json().then(data => {
        if (!response.ok) {
            const error = new Error(data.message || 'HTTP Error');
            error.status = response.status;
            error.data = data;
            throw error;
        }
        return data;
    });
}