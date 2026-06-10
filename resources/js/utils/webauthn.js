export async function isWebAuthnSupported() {
    return typeof window.PublicKeyCredential !== 'undefined';
}

function base64UrlToBase64(str) {
    return str.replace(/-/g, '+').replace(/_/g, '/');
}

function base64ToBase64Url(str) {
    return str.replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
}

function base64ToArrayBuffer(str) {
    const binary = atob(base64UrlToBase64(str));
    const bytes = new Uint8Array(binary.length);
    for (let i = 0; i < binary.length; i++) {
        bytes[i] = binary.charCodeAt(i);
    }
    return bytes.buffer;
}

function arrayBufferToBase64Url(buffer) {
    const bytes = new Uint8Array(buffer);
    let binary = '';
    for (let i = 0; i < bytes.length; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return base64ToBase64Url(btoa(binary));
}

export async function getRegisterChallenge(email) {
    const res = await fetch('/webauthn/challenge', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
        },
        body: JSON.stringify({ email }),
    });
    if (!res.ok) {
        const err = await res.json();
        throw new Error(err.message || 'Failed to get challenge');
    }
    return res.json();
}

export async function registerBiometric(email, credentialName) {
    const options = await getRegisterChallenge(email);

    options.challenge = base64ToArrayBuffer(options.challenge);
    options.user.id = base64ToArrayBuffer(options.user.id);
    options.excludeCredentials = (options.excludeCredentials || []).map(c => ({
        ...c,
        id: base64ToArrayBuffer(c.id),
    }));

    const cred = await navigator.credentials.create({ publicKey: options });

    const credential = {
        credential_id: arrayBufferToBase64Url(cred.rawId),
        public_key: arrayBufferToBase64Url(cred.response.getPublicKey()?.buffer || cred.response.attestationObject),
        name: credentialName || null,
    };

    const res = await fetch('/webauthn/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
        },
        body: JSON.stringify(credential),
    });

    if (!res.ok) {
        const err = await res.json();
        throw new Error(err.message || 'Failed to register biometric');
    }

    return res.json();
}

export async function loginBiometric() {
    const res = await fetch('/webauthn/challenge', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
        },
    });
    if (!res.ok) {
        const err = await res.json();
        throw new Error(err.message || 'Failed to get challenge');
    }
    const options = await res.json();

    const assertion = await navigator.credentials.get({
        publicKey: {
            challenge: base64ToArrayBuffer(options.challenge),
            timeout: options.timeout ?? 60000,
            rpId: options.rp?.id || window.location.hostname,
        },
    });

    const credential = {
        credential_id: arrayBufferToBase64Url(assertion.rawId),
        signature: arrayBufferToBase64Url(assertion.response.signature),
        authenticator_data: arrayBufferToBase64Url(assertion.response.authenticatorData),
        client_data_json: arrayBufferToBase64Url(assertion.response.clientDataJSON),
    };

    const authRes = await fetch('/webauthn/authenticate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
        },
        body: JSON.stringify(credential),
    });

    if (!authRes.ok) {
        const err = await authRes.json();
        throw new Error(err.message || 'Authentication failed');
    }

    return authRes.json();
}

export async function authenticateBiometric(email) {
    const options = await getRegisterChallenge(email);

    options.challenge = base64ToArrayBuffer(options.challenge);
    options.user.id = base64ToArrayBuffer(options.user.id);
    options.allowCredentials = (options.excludeCredentials || []).map(c => ({
        ...c,
        id: base64ToArrayBuffer(c.id),
    }));
    delete options.excludeCredentials;
    delete options.rp;
    delete options.user;
    delete options.pubKeyCredParams;
    delete options.attestation;
    delete options.timeout;

    if (!options.allowCredentials || options.allowCredentials.length === 0) {
        throw new Error('No biometric registered for this account');
    }

    const assertion = await navigator.credentials.get({ publicKey: options });

    const credential = {
        credential_id: arrayBufferToBase64Url(assertion.rawId),
        signature: arrayBufferToBase64Url(assertion.response.signature),
        authenticator_data: arrayBufferToBase64Url(assertion.response.authenticatorData),
        client_data_json: arrayBufferToBase64Url(assertion.response.clientDataJSON),
    };

    const res = await fetch('/webauthn/authenticate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
        },
        body: JSON.stringify(credential),
    });

    if (!res.ok) {
        const err = await res.json();
        throw new Error(err.message || 'Authentication failed');
    }

    return res.json();
}
