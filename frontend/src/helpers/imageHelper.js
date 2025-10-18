export const handleImageChange = (e, targetRef, fieldName = 'thumbnail') => {
    const file = e.target.files?.[0]
    if (!file) return
    targetRef.value[fieldName] = file
    targetRef.value[`${fieldName}_url`] = URL.createObjectURL(file)
}
