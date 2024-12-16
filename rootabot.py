# Import required libraries
import streamlit as st  # For creating web applications
import ollama  # For AI model integration
import uuid  # To generate unique identifiers for saving conversations
from typing import List, Dict  # For type hinting
import json  # For handling JSON data
import os  # For file and directory operations

# Set Page Configuration
st.set_page_config(
    page_title="RootaBot: Botanical Wisdom",  # Page title displayed in the browser
    page_icon="🌿",  # Icon for the web page
    layout="wide"  # Layout configuration to utilize the full width
)

# Custom Styling Module
class RootaBotStyles:
    # Static method to apply custom CSS styling to the Streamlit app
    @staticmethod
    def apply_custom_styling():
        # Using inline CSS for customizing the app's appearance
        st.markdown("""
        <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap'); /* Import font */
        :root {
            --primary-color: #2C8C99; /* Define primary theme color */
            --secondary-color: #5CDB95; /* Define secondary theme color */
            --accent-color: #379683; /* Define accent color */
            --background-color: #F4F9F9; /* Define background color */
            --text-color: #05386B; /* Define text color */
        }
        .stApp {
            background-color: var(--background-color); /* Set app background */
            font-family: 'Inter', sans-serif; /* Set font family */
            color: var(--text-color); /* Set text color */
        }
        .css-1aumxhk {
            background-color: rgba(92, 219, 149, 0.1); /* Sidebar styling */
            border-right: 2px solid var(--secondary-color); /* Sidebar border */
        }
        .stTextInput > div > div > input {
            border: 2px solid var(--primary-color); /* Text input border */
            border-radius: 10px; /* Round corners */
            padding: 10px; /* Padding for input */
            font-size: 16px; /* Font size */
            transition: all 0.3s ease; /* Smooth border focus animation */
        }
        .stTextInput > div > div > input:focus {
            box-shadow: 0 0 10px rgba(44, 140, 153, 0.3); /* Focus effect */
            border-color: var(--accent-color); /* Change border on focus */
        }
        .user-message {
            background-color: rgba(44, 140, 153, 0.1); /* User message background */
            border-radius: 10px; /* Rounded corners */
            padding: 15px; /* Padding inside message box */
            margin-bottom: 10px; /* Space below message */
        }
        .assistant-message {
            background-color: rgba(92, 219, 149, 0.1); /* Assistant message background */
            border-radius: 10px; /* Rounded corners */
            padding: 15px; /* Padding inside message box */
            margin-bottom: 10px; /* Space below message */
        }
        .stButton > button {
            background-color: var(--primary-color); /* Button background */
            color: white; /* Button text color */
            border: none; /* Remove default border */
            border-radius: 8px; /* Round corners */
            padding: 10px 20px; /* Button padding */
            transition: all 0.3s ease; /* Hover effect transition */
        }
        .stButton > button:hover {
            background-color: var(--accent-color); /* Change color on hover */
            transform: scale(1.05); /* Slightly enlarge button on hover */
        }
        .stSpinner > div > svg {
            color: var(--primary-color); /* Spinner color */
        }
        ::-webkit-scrollbar {
            width: 10px; /* Scrollbar width */
        }
        ::-webkit-scrollbar-track {
            background: var(--background-color); /* Scrollbar track color */
        }
        ::-webkit-scrollbar-thumb {
            background: var(--primary-color); /* Scrollbar thumb color */
            border-radius: 5px; /* Round scrollbar thumb */
        }
        </style>
        """, unsafe_allow_html=True)

# Configuration Management
class RootaBotConfig:
    # System-wide constants and configuration for the bot
    SYSTEM_PROMPT = """You are RootaBot, an expert AI assistant focused exclusively on indigenous plants and traditional medicine from Northeast India.
    
    Guidelines:
    1. Provide detailed insights only about:
       - Indigenous medicinal plants of Northeast India
       - Traditional healing practices
       - Ethnobotanical knowledge
       - Plant conservation efforts
       - Cultural significance of botanical resources

    2. Response Requirements:
       - Include scientific and local names of plants
       - Specify exact regional context (state/tribal region)
       - Describe traditional preparation methods
       - Highlight medicinal properties and uses
       - Provide historical or cultural background
       - Add appropriate medical disclaimers

    3. Maintain a scholarly, respectful, and informative tone
    4. Prioritize accuracy and depth of indigenous knowledge"""

    KEYWORDS = [
        'medicinal', 'plant', 'herb', 'traditional', 'indigenous', 
        'northeast', 'ethnobotany', 'healing', 'remedy', 'botanical',
        'assam', 'meghalaya', 'arunachal', 'nagaland', 'manipur', 
        'mizoram', 'tripura', 'sikkim', 'tribal', 'medicine'
    ]

# Conversation Management
class ConversationManager:
    @staticmethod
    def save_conversation(messages: List[Dict]):
        """Save conversation to a file with unique identifier"""
        try:
            os.makedirs("conversations", exist_ok=True)  # Ensure directory exists
            filename = f"conversations/{uuid.uuid4()}.json"  # Unique filename
            with open(filename, 'w', encoding='utf-8') as f:
                json.dump(messages, f, indent=2, ensure_ascii=False)  # Save messages as JSON
            return filename
        except Exception as e:
            st.error(f"Conversation saving failed: {e}")  # Display error in UI
            return None

    @staticmethod
    def is_relevant_query(query: str) -> bool:
        """Check query relevance based on keywords"""
        query_lower = query.lower()  # Convert query to lowercase for comparison
        return any(keyword in query_lower for keyword in RootaBotConfig.KEYWORDS)  # Check for keyword matches

# Ollama Integration
class OllamaIntegration:
    @staticmethod
    def generate_response(query: str):
        """Generate streaming response using Ollama"""
        if not ConversationManager.is_relevant_query(query):
            yield ("I can only discuss traditional medicinal plants and practices "
                   "from Northeast India. Please rephrase your question to focus on "
                   "indigenous botanical knowledge.")  # Notify irrelevant query
            return

        try:
            stream = ollama.chat(
                model='llama2',  # AI model identifier
                messages=[
                    {"role": "system", "content": RootaBotConfig.SYSTEM_PROMPT},  # System message
                    {"role": "user", "content": query}  # User query
                ],
                stream=True  # Enable response streaming
            )
            
            full_response = ""
            for chunk in stream:  # Stream each response chunk
                if 'message' in chunk:
                    content = chunk['message'].get('content', '')  # Extract content
                    if content:
                        full_response += content  # Append content to full response
                        yield content  # Yield chunked response
        except Exception as e:
            yield f"🌿 Error generating response: {str(e)}"  # Handle errors

# Main Streamlit Application
def main():
    # Apply Custom Styling
    RootaBotStyles.apply_custom_styling()

    # App Title and Description
    st.title("🌿 RootaBot: Northeast India's Botanical Heritage")  # Main title
    st.markdown("*Unveiling the rich ethnobotanical wisdom of Northeast India*")  # Subtitle

    # Sidebar Information
    with st.sidebar:
        st.header("🔬 About RootaBot")  # Sidebar title
        st.info("""
        A specialized AI assistant dedicated to:
        - 🍃 Indigenous medicinal plants
        - 🌱 Traditional healing practices
        - 🏞️ Cultural botanical knowledge
        - 🔍 Ethnobotanical research
        """)  # Sidebar info section
        
        st.header("🔗 Quick Resources")  # Resources section
        st.markdown("• [Northeast Council](https://www.necouncil.gov.in/)")  # Link
        st.markdown("• [Botanical Research Resources](https://www.bioone.org/)")  # Link
        
        st.header("🌈 Explore More")  # Exploration section
        if st.button("Reset Conversation"):  # Button to clear session state
            st.session_state.clear()

    # Initialize Chat History
    if "messages" not in st.session_state:
        st.session_state.messages = []  # Create empty list for messages

    # Display Chat History
    for msg in st.session_state.messages:
        if msg["role"] == "user":
            st.markdown(f'<div class="user-message">{msg["content"]}</div>', unsafe_allow_html=True)  # Render user messages
        else:
            st.markdown(f'<div class="assistant-message">{msg["content"]}</div>', unsafe_allow_html=True)  # Render assistant messages

    # User Input
    if prompt := st.chat_input("Ask about traditional medicine in Northeast India"):
        # User Message
        st.session_state.messages.append({"role": "user", "content": prompt})  # Append user query
        st.markdown(f'<div class="user-message">{prompt}</div>', unsafe_allow_html=True)  # Display user input

        # Assistant Response
        response_placeholder = st.empty()  # Placeholder for assistant response
        full_response = ""
        
        # Stream the response
        for response_chunk in OllamaIntegration.generate_response(prompt):
            full_response += response_chunk  # Build complete response
            response_placeholder.markdown(f'<div class="assistant-message">{full_response}▌</div>', unsafe_allow_html=True)  # Update placeholder

        # Save the complete response
        response_placeholder.markdown(f'<div class="assistant-message">{full_response}</div>', unsafe_allow_html=True)
        st.session_state.messages.append({"role": "assistant", "content": full_response})  # Append assistant response
        

if __name__ == "__main__":
    main()  # Run the application
