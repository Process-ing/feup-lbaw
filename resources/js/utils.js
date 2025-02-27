const sendGet = (url) => {
	return fetch(url, {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
		}
	});
}

const encodeParams = (params) => {
	if (!params) {
		return '';
	}
	return '?' + Object.keys(params).map(key => {
		return !Array.isArray(params[key])
			? encodeURIComponent(key) + '=' + encodeURIComponent(params[key] ?? '')
			: params[key].map(value => encodeURIComponent(key) + '[]=' + encodeURIComponent(value ?? '')).join('&');
	}).filter(str => str !== '').join('&');
}

const getView = (url, params) => {
	return fetch(url + encodeParams(params), {
		method: 'GET',
		headers: {
			'Accept': 'text/html',
			'X-Requested-With': 'XMLHttpRequest',
		},
	}).then(response => {
		if (!response.ok) {
			throw new Error('Unexpected error occurred');
		}
		return response.text();
	});
}

const sendPostView = (url, data) => {
    return fetch(url, {
        method: 'POST',
        headers: {
			'Accept': 'text/html',
			'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
        },
		body: JSON.stringify(data),
    }).then(response => {
		if (!response.ok) {
			throw new Error('Unexpected error occurred');
		}
		return response.text();
	});
};

const sendPutView = (url, data) => {
	return fetch(url, {
		method: 'PUT',
		headers: {
			'Accept': 'text/html',
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			'X-Requested-With': 'XMLHttpRequest',
		},
		body: JSON.stringify(data),
	}).then(response => {
		if (!response.ok) {
			throw new Error('Unexpected error occurred');
		}
		return response.text();
	});
};

const sendPost = (url, data) => {
	return fetch(url, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			'X-Requested-With': 'XMLHttpRequest',
		},
		body: JSON.stringify(data),
	}).then(response => {
		return response.json().then(json => {
			if (!response.ok) {
				throw new Error(json.error);
			}
			return json;
		})
	});
}

const sendPut = (url, data) => {
	return fetch(url, {
		method: 'PUT',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			'X-Requested-With': 'XMLHttpRequest',
		},
		body: JSON.stringify(data)
	}).then(response => {
		return response.json().then(json => {
			if (!response.ok) {
				throw new Error(json.error);
			}
			return json;
		})
	});
}

const sendDelete = (url) => {
	return fetch(url, {
		method: 'DELETE',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			'X-Requested-With': 'XMLHttpRequest',
		}
	}).then(response => {
		return response.json().then(json => {
			if (!response.ok) {
				throw new Error(json.error);
			}
			return json;
		})
	});
}

const fadeToastMessage = (toastMessage) => {
	if (toastMessage.dataset.timeoutId) {
		clearTimeout(toastMessage.dataset.timeoutId);
	}

	toastMessage.dataset.timeoutId = setTimeout(() => {
		toastMessage.classList.add('opacity-0');
		toastMessage.dataset.timeoutId = setTimeout(() => {
			toastMessage.classList.add('hidden');
			toastMessage.classList.remove('opacity-0');
		}, 500);
	}, 3000);
}

const sendToastMessage = (message, type) => {
	let toast;
	switch (type) {
		case 'success':
			toast = 'success-toast';
			break;
		case 'error':
			toast = 'error-toast';
			break;
		case 'info':
			toast = 'info-toast';
			break;
		default:
			return;
	}

	const toastMessage = document.getElementById(toast);
	const toastMessageText = toastMessage.querySelector(':scope > p');
	toastMessageText.textContent = message;

	toastMessage.classList.remove('hidden');
	toastMessage.classList.remove('opacity-0');
	fadeToastMessage(toastMessage);
}

const loadMoreElements = async (container, endpoint, params = {}, page) => {
	const posts = await getView(endpoint, { ...params, page: page });

	if (posts.trim() !== '') {
		container.insertAdjacentHTML('beforeend', posts);
		return false;
	} else {
		return true;
	}
}

const addLazyLoading = (container, containerLoading, endpoint, params, callback) => {
	let loading = false;
	let atEnd = false;
	let page = 1;

	document.addEventListener('scrollend', async () => {
		if (!atEnd && !loading && window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
			loading = true;
			containerLoading.classList.remove('hidden');

			page++;
			atEnd = await loadMoreElements(container, endpoint, params, page);
			if (callback) {
				callback();
			}

			loading = false;
			containerLoading.classList.add('hidden');
		}
	});
}

const addLazyLoadingContainer = (container, containerLoading, endpoint, params, callback) => {
    let loading = false;
    let atEnd = false;
    let page = 1;

    const scrollableElement = container.querySelector('.flex-1.overflow-y-auto');

    if (!scrollableElement) {
        return;
    }

    const onScroll = async () => {
        const scrollTop = scrollableElement.scrollTop;
        const scrollHeight = scrollableElement.scrollHeight;
        const clientHeight = scrollableElement.clientHeight;

        if (!atEnd && !loading && scrollTop + clientHeight >= scrollHeight - 100) {
            loading = true;
            containerLoading.classList.remove('hidden');

            page++;
            atEnd = await loadMoreElements(scrollableElement, endpoint, params, page);
            if (callback) {
                callback();
            }

            loading = false;
            containerLoading.classList.add('hidden');
        }
    };

    scrollableElement.addEventListener('scroll', onScroll);
};

export { sendGet, encodeParams, getView,sendPostView, sendPutView, sendDelete, sendPost, sendPut, fadeToastMessage, sendToastMessage, addLazyLoading, addLazyLoadingContainer };
