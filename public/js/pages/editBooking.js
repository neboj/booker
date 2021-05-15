document.addEventListener('DOMContentLoaded', function () {
    const dateEl = document.getElementById('booking_datetime_date');
    const dayEl = document.getElementById('booking_datetime_date_day');
    const monthEl = document.getElementById('booking_datetime_date_month');
    const yearEl = document.getElementById('booking_datetime_date_year');
    const hourEl = document.getElementById('booking_datetime_time_hour');
    const minuteEl = document.getElementById('booking_datetime_time_minute');

    const datetime = dateEl.getAttribute('data-current__date');
    const [date,time] = datetime.split('-');
    const [day,month,year] = date.split('.');
    const [hour,minute] = time.split('.');

    const optionHourEl = hourEl.querySelector('option[value="' + parseInt(hour) + '"]');
    const optionMinuteEl = minuteEl.querySelector('option[value="' + parseInt(minute) + '"]');
    const optionMonthEl = monthEl.querySelector('option[value="' + parseInt(month) + '"]');
    const optionDayEl = dayEl.querySelector('option[value="' + parseInt(day) + '"]');
    const optionYearEl = yearEl.querySelector('option[value="' + parseInt(year) + '"]');

    const setDefaultFormOptions = () => {
        optionHourEl.selected = 'selected';
        optionMinuteEl.selected = 'selected';
        optionMonthEl.selected = 'selected';
        optionDayEl.selected = 'selected';
        optionYearEl.selected = 'selected';
    }

    setDefaultFormOptions();
});