const utils = {

    // return a string with the first letter in uppercase
    capitalize(str)
    {
        return str[0].toUpperCase() + str.slice(1);
    },

    // return a truncate string if its length is greater than parameter 'number'
    truncateString: function(str, number)
    {
        return (str.length <= number) ? str : str.slice(0, number) + '...';
    }
}