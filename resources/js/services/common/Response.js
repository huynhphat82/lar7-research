const json = (message, code = 200, success = true) => ({
  success: success,
  status_code: code,
  //message_type: 'SUCCESS', // 'WARNING' | 'FAILED'
  [success ? 'data' : 'error']: message
});

const Response = {
  success: (message, code) => json(message, code),
  error: (message, code) => json(message, code, false)
};

export default Response;
