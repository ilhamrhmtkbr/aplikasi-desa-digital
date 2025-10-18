import numeral from 'numeral'
import {DateTime} from 'luxon'

export function formatRupiah(value) {
    return numeral(value).format('0,0[.]00')
}

export function parseRupiah(value) {
    return numeral(value).value()
}

export function formatPercentage(value) {
    return numeral(value).format('0,0[.]00%')
}

export function formatDate(date) {
    const options = {day: 'numeric', month: 'long', year: 'numeric'}

    return new Date(date).toLocaleDateString('id-ID', options)
}

export function formatDateTime(date) {
    const options = {day: 'numeric', month: 'long', year: 'numeric', hour: 'numeric', minute: 'numeric'}

    return new Date(date).toLocaleDateString('id-ID', options)
}

export function formatClientTimezone(date) {
    const originalDate = DateTime.fromISO(date, {zone: 'utc'})
    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

    return originalDate
        .setZone(timezone)
        .setLocale('id')
        .toFormat('dd LLLL yyyy, HH:mm')
}

export function getDateDifference(startDate, endDate) {
    const start = new Date(startDate);
    const end = new Date(endDate);
    const diffTime = end - start;
    return diffTime / (1000 * 60 * 60 * 24); // konversi ms ke hari
}

export function ucFirst(string) {
    return string ? string.charAt(0).toUpperCase() + string.slice(1) : '';
}

export const calculateAge = (dateString) => {
    const today = new Date();
    const birthDate = new Date(dateString);
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
};