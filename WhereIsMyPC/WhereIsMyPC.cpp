// WhereIsMyPC.cpp : This file contains the 'main' function. Program execution begins and ends there.
//

#include <iostream>
#include <string>
#include <vector>
#include <windows.h>
#include <lmcons.h>

#pragma comment(lib, "Ws2_32.lib")

using namespace std;

#define BUFFERSIZE 1024

wstring getComputerName()
{
	vector<wchar_t> buffer;
	buffer.resize(MAX_COMPUTERNAME_LENGTH + 1);
	DWORD size = MAX_COMPUTERNAME_LENGTH;

	GetComputerNameW(&buffer[0], &size);

	return wstring(&buffer[0], size);
} // getComputerName


std::string ws2s(const std::wstring& wstr, const std::locale& loc)
{
	if (wstr.empty())
	{
		return std::string();
	}

	typedef std::wstring::traits_type::state_type state_type;
	typedef std::codecvt<wchar_t, char, state_type> convert;

	const convert& cvt = std::use_facet<convert>(loc);
	std::string str(cvt.max_length() * wstr.size(), '\0');
	state_type state = state_type();

	const wchar_t* from_beg = &wstr[0];
	const wchar_t* from_end = from_beg + wstr.size();
	const wchar_t* from_nxt;
	char* to_beg = &str[0];
	char* to_end = to_beg + str.size();
	char* to_nxt;

	std::string::size_type sz = 0;
	std::codecvt_base::result r;
	do
	{
		r = cvt.out(state, from_beg, from_end, from_nxt, to_beg, to_end, to_nxt);
		switch (r)
		{
		case std::codecvt_base::error:
			throw std::runtime_error("error converting wstring to string");

		case std::codecvt_base::partial:
			sz += to_nxt - to_beg;
			str.resize(2 * str.size());
			to_beg = &str[sz];
			to_end = &str[0] + str.size();
			break;

		case std::codecvt_base::noconv:
			str.resize(sz + (from_end - from_beg) * sizeof(wchar_t));
			std::memcpy(&str[sz], from_beg, (from_end - from_beg) * sizeof(wchar_t));
			r = std::codecvt_base::ok;
			break;

		case std::codecvt_base::ok:
			sz += to_nxt - to_beg;
			str.resize(sz);
			break;
		}
	} while (r != std::codecvt_base::ok);

	return str;
} // ws2s


int main()
{
	//PC name and Operator's Name
	string string_params[2];

	string_params[0] = ws2s(getComputerName(), std::locale("")); //Get Computername

	TCHAR name[UNLEN + 1];
	DWORD size = UNLEN + 1;

	if (GetUserName((TCHAR*)name, &size)) {
		std::wstring arr_w(name);

		string_params[1] = ws2s(arr_w, std::locale(""));
	}

	WSADATA wsaData;

	if (WSAStartup(MAKEWORD(2, 2), &wsaData) != 0) {
		return 1;
	}

	SOCKET Socket = socket(AF_INET, SOCK_STREAM, IPPROTO_TCP);
	SOCKADDR_IN SockAddr;

	struct hostent* host;
	host = gethostbyname("localhost");

	SockAddr.sin_port = htons(80);
	SockAddr.sin_family = AF_INET;
	SockAddr.sin_addr.s_addr = *((unsigned long*)host->h_addr);

	if (connect(Socket, (SOCKADDR*)(&SockAddr), sizeof(SockAddr)) != 0) 
	{
		return 1;
	}

	string request;
	int resp_leng;

	char buffer[BUFFERSIZE];
	
	string string_tosend = "pc_id=" + string_params[0] + "&pc_dscr=" + string_params[1];

	request += "GET /WhereIAm/add.php?" + string_tosend + " HTTP/1.0\r\n";
	request += "Host: localhost\r\n\r\n";
	request += "\r\n";

	//send request
	send(Socket, request.c_str(), request.length(), 0);

	//get response
	string response("");

	resp_leng = BUFFERSIZE;

	while (resp_leng == BUFFERSIZE)
	{
		resp_leng = recv(Socket, (char*)&buffer, BUFFERSIZE, 0);
		if (resp_leng > 0)
		{
			response += string(buffer).substr(0, resp_leng);
		}
	}

	closesocket(Socket);

	WSACleanup();

	return 0;

} // main
