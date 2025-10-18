export function buildFormData(obj) {
    const fd = new FormData();

    Object.keys(obj).forEach(key => {
        const value = obj[key];

        // skip field yang frontend-only
        if (key === 'thumbnail_url') return;

        // kalau ada file baru
        if (key === 'thumbnail' && value instanceof File) {
            fd.append('thumbnail', value);
            return;
        }

        // skip null/undefined
        if (value === null || value === undefined) return;

        fd.append(key, value);
    });

    return fd;
}
